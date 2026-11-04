<?php

declare(strict_types=1);

namespace App\Scout\Factories;

use BackedEnum;
use Elastic\Adapter\Search\SearchParameters;
use Elastic\ScoutDriver\Factories\SearchParametersFactory;
use Laravel\Scout\Builder;
use stdClass;

class OptimizedSearchParametersFactory extends SearchParametersFactory
{
    public function makeFromBuilder(Builder $builder, array $options = []): SearchParameters
    {
        $searchParameters = new SearchParameters;

        $index = $this->makeIndex($builder);
        $searchParameters->indices([$index]);

        $query = $this->makeQuery($builder);
        $searchParameters->query($query);

        if ($sort = $this->makeSort($builder)) {
            $searchParameters->sort($sort);
        }

        // Quy tắc 4: Giới hạn phạm vi trả về bằng _source filtering và fields
        if ($source = $this->makeSource($builder, $index)) {
            $searchParameters->source($source);
        }

        if ($fields = $this->makeFields($builder, $index)) {
            $searchParameters->fields($fields);
        }

        // Quy tắc 3: Ưu tiên search_after thay vì from/size lớn
        $searchAfter = $options['search_after'] ?? ($builder->options['search_after'] ?? null);

        if (is_array($searchAfter) && $searchAfter !== []) {
            $searchParameters->searchAfter(array_values($searchAfter));
        } elseif (($from = $this->makeFrom($options)) !== null) {
            $searchParameters->from($from);
        }

        if ($size = $this->makeSize($builder, $options)) {
            $searchParameters->size($size);
        }

        return $searchParameters;
    }

    /**
     * Quy tắc 1: Luôn ưu tiên Filter Context (bool.filter) cho các trường cố định (_score = 0).
     * Khi không có từ khóa full-text, đặt match_all trong filter thay vì must để tận dụng Node Query Cache.
     */
    protected function makeQuery(Builder $builder): array
    {
        $index = $this->makeIndex($builder);
        $indexSettings = config("scout.elasticsearch.index-settings.{$index}", []);
        $searchableFields = $indexSettings['searchableAttributes'] ?? ['*'];

        $bool = [];
        $keyword = trim((string) $builder->query);

        if ($keyword !== '') {
            $bool['must'] = [
                [
                    'multi_match' => [
                        'query' => $keyword,
                        'fields' => $searchableFields,
                        'fuzziness' => 'AUTO',
                    ],
                ],
            ];
        }

        $filters = $this->buildFilterAndMustNot($builder);

        if (! empty($filters['filter'])) {
            $bool['filter'] = $filters['filter'];
        } elseif ($keyword === '') {
            // Không tính điểm (_score = 0) khi chỉ duyệt danh sách không có từ khóa
            $bool['filter'] = [
                ['match_all' => new stdClass],
            ];
        }

        if (! empty($filters['must_not'])) {
            $bool['must_not'] = $filters['must_not'];
        }

        return ['bool' => $bool];
    }

    /**
     * Tách toàn bộ điều kiện cố định (status, ID, khoảng giá, ngày tháng, whereIn)
     * vào khối filter và must_not, tuyệt đối không dùng must.
     *
     * @return array{filter: array<int, array<string, mixed>>, must_not: array<int, array<string, mixed>>}
     */
    public function buildFilterAndMustNot(Builder $builder): array
    {
        $filter = [];
        $mustNot = [];

        foreach ($builder->wheres as $key => $where) {
            $field = is_array($where) ? (string) ($where['field'] ?? $key) : (string) $key;
            $operator = is_array($where) ? (string) ($where['operator'] ?? '=') : '=';
            $value = is_array($where) ? ($where['value'] ?? null) : $where;

            if ($value instanceof BackedEnum) {
                $value = $value->value;
            }

            if ($operator === '!=') {
                $mustNot[] = ['term' => [$field => $value]];
            } else {
                $filter[] = $this->makeWhereClause($field, $operator, $value);
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

    /**
     * Quy tắc 3: Luôn đảm bảo có tie-breaker sort theo khóa chính (id) để hỗ trợ search_after.
     */
    protected function makeSort(Builder $builder): ?array
    {
        $keyName = $builder->model->getScoutKeyName();

        $sort = collect($builder->orders)->map(static fn (array $order) => [
            $order['column'] => ['order' => $order['direction']],
        ])->values()->all();

        $hasKeySort = collect($builder->orders)->contains(
            static fn (array $order) => ($order['column'] ?? null) === $keyName
        );

        if (! $hasKeySort) {
            if ($sort === [] && trim((string) $builder->query) !== '') {
                $sort[] = ['_score' => ['order' => 'desc']];
            }
            $sort[] = [$keyName => ['order' => 'asc']];
        }

        return $sort;
    }

    /**
     * Quy tắc 3: Giới hạn deep offset (from/size lớn) để tránh lỗi circuit_breaking_exception.
     */
    protected function makeFrom(array $options): ?int
    {
        if (isset($options['page'], $options['perPage'])) {
            $from = max(0, ((int) $options['page'] - 1) * (int) $options['perPage']);
            $maxWindow = (int) config('scout.elasticsearch.max_from_window', 1000);

            return min($from, $maxWindow);
        }

        return null;
    }

    /**
     * Quy tắc 4: Chỉ định rõ các trường cần lấy trong _source.
     *
     * @return array<int, string>|bool|null
     */
    public function makeSource(Builder $builder, string $index): array|bool|null
    {
        if (array_key_exists('_source', $builder->options)) {
            return $builder->options['_source'];
        }

        $configuredSource = config("scout.elasticsearch.index-settings.{$index}._source");

        if (is_array($configuredSource) && $configuredSource !== []) {
            $keyName = $builder->model->getScoutKeyName();

            return array_values(array_unique(array_merge([$keyName], $configuredSource)));
        }

        return null;
    }

    /**
     * Quy tắc 4: Chỉ định các trường docvalue fields.
     *
     * @return array<int, string>|null
     */
    public function makeFields(Builder $builder, string $index): ?array
    {
        if (array_key_exists('fields', $builder->options)) {
            return $builder->options['fields'];
        }

        $configuredFields = config("scout.elasticsearch.index-settings.{$index}.fields");

        return is_array($configuredFields) && $configuredFields !== []
            ? array_values($configuredFields)
            : null;
    }
}
