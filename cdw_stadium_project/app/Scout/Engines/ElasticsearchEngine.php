<?php

namespace App\Scout\Engines;

use BackedEnum;
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

    public function __construct(array $config = [], bool $softDelete = false)
    {
        $this->config = $config;
        $this->softDelete = $softDelete;
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
            'size' => $builder->limit ?? 1000,
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
        $perPage = (int) $perPage;
        $page = max(1, (int) $page);

        return $this->performSearch($builder, [
            'from' => ($page - 1) * $perPage,
            'size' => $perPage,
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

    protected function buildSearchBody(Builder $builder, array $options = []): array
    {
        $index = $builder->index ?: $builder->model->searchableAs();
        $indexSettings = $this->config['index-settings'][$index] ?? [];
        $searchableFields = $indexSettings['searchableAttributes'] ?? ['*'];

        $must = [];
        $query = trim((string) $builder->query);

        if ($query !== '') {
            $must[] = [
                'multi_match' => [
                    'query' => $query,
                    'fields' => $searchableFields,
                    'fuzziness' => 'AUTO',
                ],
            ];
        } else {
            $must[] = ['match_all' => (object) []];
        }

        $filters = $this->filters($builder);

        $boolQuery = ['must' => $must];

        if (! empty($filters['filter'])) {
            $boolQuery['filter'] = $filters['filter'];
        }

        if (! empty($filters['must_not'])) {
            $boolQuery['must_not'] = $filters['must_not'];
        }

        $body = array_merge($builder->options, $options, [
            'query' => [
                'bool' => $boolQuery,
            ],
        ]);

        $sort = $this->buildSortFromOrderByClauses($builder);

        if (! empty($sort)) {
            $body['sort'] = $sort;
        }

        return $body;
    }

    protected function filters(Builder $builder): array
    {
        $filter = [];
        $mustNot = [];

        foreach ($builder->wheres as $key => $where) {
            $field = is_array($where) ? ($where['field'] ?? $key) : $key;
            $value = is_array($where) ? ($where['value'] ?? null) : $where;
            $operator = is_array($where) ? ($where['operator'] ?? '=') : '=';

            if ($value instanceof BackedEnum) {
                $value = $value->value;
            }

            if ($operator === '!=') {
                $mustNot[] = ['term' => [$field => $value]];
            } else {
                $filter[] = ['term' => [$field => $value]];
            }
        }

        if (property_exists($builder, 'whereIns')) {
            foreach ($builder->whereIns as $field => $values) {
                $filter[] = ['terms' => [$field => array_values($values)]];
            }
        }

        if (property_exists($builder, 'whereNotIns')) {
            foreach ($builder->whereNotIns as $field => $values) {
                $mustNot[] = ['terms' => [$field => array_values($values)]];
            }
        }

        return [
            'filter' => $filter,
            'must_not' => $mustNot,
        ];
    }

    protected function buildSortFromOrderByClauses(Builder $builder): array
    {
        return collect($builder->orders)
            ->map(fn (array $order) => [$order['column'] => ['order' => $order['direction']]])
            ->all();
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  mixed  $results
     * @return Collection
     */
    public function mapIds($results)
    {
        $hits = $results['hits']['hits'] ?? [];

        if (empty($hits)) {
            return collect();
        }

        return collect($hits)->map(function (array $hit) {
            return $hit['_source']['id'] ?? $hit['_id'] ?? null;
        })->filter(fn ($id) => $id !== null)->values();
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
            return $hit['_source'][$key] ?? $hit['_id'] ?? null;
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

        $this->client()->post("/{$index}/_delete_by_query?refresh=true", [
            'query' => [
                'match_all' => (object) [],
            ],
        ]);
    }

    /**
     * Create a search index.
     *
     * @param  string  $name
     * @return mixed
     */
    public function createIndex($name, array $options = [])
    {
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
     * Update the index settings for the given index.
     *
     * @return void
     */
    public function updateIndexSettings(string $name, array $settings = [])
    {
        $this->createIndex($name);

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
