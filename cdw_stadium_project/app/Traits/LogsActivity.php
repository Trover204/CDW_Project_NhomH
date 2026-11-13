<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
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

    public function logAction(string $action): void
    {
        $classParts = explode('\\', get_class($this));
        $modelName = end($classParts);
        $modelLabel = ActivityLog::MODEL_LABELS[$modelName] ?? $modelName;
        $actionLabel = ActivityLog::ACTION_LABELS[$action] ?? $action;

        $displayTitle = $this->resolveActivityDisplayTitle();
        $suffix = $displayTitle !== '' ? " — {$displayTitle}" : '';

        $description = "[{$actionLabel} {$modelLabel}] {$action} {$modelName} ID: {$this->id}{$suffix}";

        ActivityLog::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()?->ip(),
        ]);
    }

    protected function resolveActivityDisplayTitle(): string
    {
        foreach (['name', 'title', 'transaction_code', 'customer_name', 'email'] as $attr) {
            $val = $this->getAttribute($attr);
            if (is_string($val) && trim($val) !== '') {
                return trim($val);
            }
        }

        $content = $this->getAttribute('content');
        if (is_string($content) && trim($content) !== '') {
            return mb_strimwidth(trim($content), 0, 50, '...');
        }

        return '';
    }
}


