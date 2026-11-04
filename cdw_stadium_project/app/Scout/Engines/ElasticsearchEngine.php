<?php

namespace App\Scout\Engines;

use App\Scout\Factories\OptimizedSearchParametersFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\LazyCollection;
use Laravel\Scout\Builder;
use Laravel\Scout\Contracts\UpdatesIndexSettings;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Jobs\RemoveableScoutCollection;

class ElasticsearchEngine extends Engine implements UpdatesIndexSettings
{
    protected array $config;

    protected bool $softDelete;

    protected OptimizedSearchParametersFactory $parametersFactory;

    public function __construct(
        array $config = [],
        bool $softDelete = false,
        ?OptimizedSearchParametersFactory $parametersFactory = null
    ) {
        $this->config = $config;
        $this->softDelete = $softDelete;
        $this->parametersFactory = $parametersFactory ?? new OptimizedSearchParametersFactory;
    }

    protected function client(): PendingRequest
    {
        $host = rtrim($this->config['host'] ?? 'http://localhost:9200', '/');
        $request = Http::baseUrl($host)->acceptJson();

        if (! empty($this->config['api_key'])) {
            $request = $request->withToken($this->config['api_key'], 'ApiKey');
        } elseif (! empty($this->config['username'])) {
            $request = $request->withBasicAuth(
                (string) $this->config['username'],
                (string) ($this->config['password'] ?? '')
            );
        }

        return $request;
    }

    /**
     * Update the given model in the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $models
     * @return void
     */
    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $firstModel = $models->first();
        $index = $firstModel->indexableAs();

        if ($this->usesSoftDelete($firstModel) && $this->softDelete) {
            $models->each->pushSoftDeleteMetadata();
        }

        $lines = [];

        foreach ($models as $model) {
            $searchableData = $model->toSearchableArray();

            if (empty($searchableData)) {
                continue;
            }

            $document = array_merge(
                $searchableData,
                $model->scoutMetadata(),
                [$model->getScoutKeyName() => $model->getScoutKey()]
            );

            $lines[] = json_encode([
                'index' => [
                    '_index' => $index,
                    '_id' => (string) $model->getScoutKey(),
                ],
            ]);
            $lines[] = json_encode($document);
        }

        if (empty($lines)) {
            return;
        }

        $payload = implode("\n", $lines)."\n";

        $this->client()
            ->withBody($payload, 'application/x-ndjson')
            ->post('/_bulk?refresh=true');
    }

    /**
     * Remove the given model from the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $models
     * @return void
     */
    public function delete($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $firstModel = $models->first();
        $index = $firstModel->indexableAs();

        $keys = $models instanceof RemoveableScoutCollection
            ? $models->pluck($firstModel->getScoutKeyName())
            : $models->map->getScoutKey();

        $lines = [];

        foreach ($keys as $key) {
            $lines[] = json_encode([
                'delete' => [
                    '_index' => $index,
                    '_id' => (string) $key,
                ],
            ]);
        }

        if (empty($lines)) {
            return;
        }

        $payload = implode("\n", $lines)."\n";

        $this->client()
            ->withBody($payload, 'application/x-ndjson')
            ->post('/_bulk?refresh=true');
    }

    /**
     * Perform the given search on the engine.
     *
     * @return mixed
     */
    public function search(Builder $builder)
    {
        return $this->performSearch($builder, array_filter([
            'perPage' => $builder->limit ?? 1000,
        ]));
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  int  $perPage
     * @param  int  $page
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        return $this->performSearch($builder, [
            'perPage' => (int) $perPage,
            'page' => max(1, (int) $page),
        ]);
    }

    protected function performSearch(Builder $builder, array $options = []): array
    {
        $index = $builder->index ?: $builder->model->searchableAs();
        $body = $this->buildSearchBody($builder, $options);

        if ($builder->callback) {
            return call_user_func(
                $builder->callback,
                $this->client(),
                $builder->query,
                $body
            );
        }

        $response = $this->client()->post("/{$index}/_search", $body);

        if (! $response->successful()) {
            return [
                'hits' => [
                    'total' => ['value' => 0],
                    'hits' => [],
                ],
            ];
        }

        return $response->json() ?? [
            'hits' => [
                'total' => ['value' => 0],
                'hits' => [],
            ],
        ];
    }

    /**
     * Xây dựng cấu trúc truy vấn Elasticsearch áp dụng 5 Quy tắc vàng:
     * 1. Filter Context (bool.filter) cho điều kiện cố định (_score = 0).
     * 2. Mapping keyword vs text đã định nghĩa trong config/scout.php.
     * 3. Phân trang tối ưu bằng search_after (không gửi from khi dùng search_after, giới hạn max_from_window).
     * 4. Giới hạn dữ liệu trả về bằng _source filtering và fields.
     * 5. Norms: false & index_options: freqs trong mapping.
     */
    public function buildSearchBody(Builder $builder, array $options = []): array
    {
        $searchParameters = $this->parametersFactory->makeFromBuilder($builder, $options)->toArray();
        $body = $searchParameters['body'] ?? [];

        // Cho phép ghi đè tuỳ chọn từ $builder->options (trừ những khóa điều khiển riêng)
        foreach ($builder->options as $key => $value) {
            if (! in_array($key, ['search_after', '_source', 'fields'], true)) {
                $body[$key] = $value;
            }
        }

        return $body;
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  mixed  $results
     * @return Collection
     */
    public function mapIds($results)
    {
        return $this->mapIdsFrom($results, 'id');
    }

    /**
     * Pluck and return the primary keys of the given results using the given key name.
     *
     * @param  mixed  $results
     * @param  string  $key
     * @return Collection
     */
    public function mapIdsFrom($results, $key)
    {
        $hits = $results['hits']['hits'] ?? [];

        if (empty($hits)) {
            return collect();
        }

        return collect($hits)->map(function (array $hit) use ($key) {
            if (isset($hit['_source'][$key])) {
                return $hit['_source'][$key];
            }

            if (isset($hit['fields'][$key][0])) {
                return $hit['fields'][$key][0];
            }

            return $hit['_id'] ?? null;
        })->filter(fn ($id) => $id !== null)->values();
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param  mixed  $results
     * @param  Model  $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        $hits = $results['hits']['hits'] ?? [];

        if (empty($hits)) {
            return $model->newCollection();
        }

        $keyName = $model->getScoutKeyName();
        $objectIds = $this->mapIdsFrom($results, $keyName)->all();
        $objectIdPositions = array_flip($objectIds);

        return $model->getScoutModelsByIds($builder, $objectIds)
            ->filter(fn ($model) => in_array($model->getScoutKey(), $objectIds))
            ->sortBy(fn ($model) => $objectIdPositions[$model->getScoutKey()])
            ->values();
    }

    /**
     * Map the given results to instances of the given model via a lazy collection.
     *
     * @param  mixed  $results
     * @param  Model  $model
     * @return LazyCollection
     */
    public function lazyMap(Builder $builder, $results, $model)
    {
        $hits = $results['hits']['hits'] ?? [];

        if (empty($hits)) {
            return LazyCollection::make($model->newCollection());
        }

        $keyName = $model->getScoutKeyName();
        $objectIds = $this->mapIdsFrom($results, $keyName)->all();
        $objectIdPositions = array_flip($objectIds);

        return $model->queryScoutModelsByIds($builder, $objectIds)
            ->cursor()
            ->filter(fn ($model) => in_array($model->getScoutKey(), $objectIds))
            ->sortBy(fn ($model) => $objectIdPositions[$model->getScoutKey()])
            ->values();
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param  mixed  $results
     * @return int
     */
    public function getTotalCount($results)
    {
        $total = $results['hits']['total'] ?? 0;

        return is_array($total) ? (int) ($total['value'] ?? 0) : (int) $total;
    }

    /**
     * Flush all of the model's records from the engine.
     *
     * @param  Model  $model
     * @return void
     */
    public function flush($model)
    {
        $index = $model->indexableAs();

        $this->client()->post("/{$index}/_delete_by_query?refresh=true&ignore_unavailable=true", [
            'query' => [
                'match_all' => (object) [],
            ],
        ]);
    }

    /**
     * Create a search index with optimized mappings (Rule 2 & Rule 5).
     *
     * @param  string  $name
     * @return mixed
     */
    public function createIndex($name, array $options = [])
    {
        $indexSettings = $this->config['index-settings'][$name] ?? [];

        if (empty($options) && ! empty($indexSettings['mappings'])) {
            $options = [
                'mappings' => $indexSettings['mappings'],
            ];
        }

        return $this->client()->put("/{$name}", $options ?: (object) [])->json();
    }

    /**
     * Delete a search index.
     *
     * @param  string  $name
     * @return mixed
     */
    public function deleteIndex($name)
    {
        return $this->client()->delete("/{$name}")->json();
    }

    /**
     * Update the index settings and mappings for the given index (Rule 2 & Rule 5).
     *
     * @return void
     */
    public function updateIndexSettings(string $name, array $settings = [])
    {
        $settings = $settings ?: ($this->config['index-settings'][$name] ?? []);

        $this->createIndex($name, ! empty($settings['mappings']) ? ['mappings' => $settings['mappings']] : []);

        if (! empty($settings['mappings'])) {
            $this->client()->put("/{$name}/_mapping", $settings['mappings']);
        }
    }

    /**
     * Configure the soft delete filter within the given settings.
     *
     * @return array
     */
    public function configureSoftDeleteFilter(array $settings = [])
    {
        $settings['filterableAttributes'][] = '__soft_deleted';

        return $settings;
    }

    protected function usesSoftDelete($model): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($model));
    }
}
