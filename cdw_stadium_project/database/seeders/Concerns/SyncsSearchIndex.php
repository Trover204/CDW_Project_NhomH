<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\Artisan;
use Laravel\Scout\Contracts\UpdatesIndexSettings;
use Laravel\Scout\ModelObserver;
use Laravel\Scout\Searchable;
use Throwable;

trait SyncsSearchIndex
{
    /**
     * Đồng bộ cấu hình Mapping (Quy tắc 2 & 5) và toàn bộ dữ liệu của Model lên Elasticsearch.
     * Nếu đang chạy trong DatabaseSeeder (syncing đang tạm tắt), việc import sẽ
     * được gom lại chạy một lần ở cuối DatabaseSeeder.
     */
    protected function syncSearchIndex(string $modelClass): void
    {
        if (! $this->isModelSearchable($modelClass)) {
            return;
        }

        if (ModelObserver::syncingDisabledFor($modelClass)) {
            return;
        }

        try {
            $modelInstance = new $modelClass;
            $indexName = $modelInstance->indexableAs();
            $indexSettings = config("scout.elasticsearch.index-settings.{$indexName}", []);
            $engine = $modelInstance->searchableUsing();

            if ($engine instanceof UpdatesIndexSettings && ! empty($indexSettings)) {
                $engine->updateIndexSettings($indexName, $indexSettings);
            }

            if ($this->command) {
                $this->command->call('scout:flush', ['model' => $modelClass]);
                $this->command->call('scout:import', ['model' => $modelClass]);
            } else {
                Artisan::call('scout:flush', ['model' => $modelClass]);
                Artisan::call('scout:import', ['model' => $modelClass]);
            }
        } catch (Throwable $e) {
            if ($this->command) {
                $this->command->warn("Không thể đồng bộ [{$modelClass}] lên Elasticsearch: {$e->getMessage()}");
            }
        }
    }

    protected function isModelSearchable(string $modelClass): bool
    {
        return class_exists($modelClass)
            && in_array(Searchable::class, class_uses_recursive($modelClass));
    }
}
