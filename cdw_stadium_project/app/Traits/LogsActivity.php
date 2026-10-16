<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logAction('Created');
        });

        static::updated(function ($model) {
            $model->logAction('Updated');
        });

        static::deleted(function ($model) {
            $model->logAction('Deleted');
        });
    }

    protected function logAction($action)
    {
        $classParts = explode('\\', get_class($this));
        $modelName = end($classParts);

        $description = "{$action} {$modelName} ID: {$this->id}";

        ActivityLog::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
