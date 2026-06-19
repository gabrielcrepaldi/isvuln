<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Laravel calls this automatically when the trait is booted on a model.
     * We hook into the model's lifecycle events here.
     */
    public static function bootAuditable(): void
    {
        // Fires after a new record is created
        static::created(function ($model) {
            AuditLog::record('created', $model, null, $model->auditScrub($model->getAttributes()));
        });

        // Fires after a record is updated
        static::updated(function ($model) {
            AuditLog::record(
                'updated',
                $model,
                $model->auditScrub($model->getOriginal()),   // state before the change
                $model->auditScrub($model->getChanges())     // only the fields that actually changed
            );
        });

        // Fires after a record is deleted
        static::deleted(function ($model) {
            AuditLog::record('deleted', $model, $model->auditScrub($model->getAttributes()), null);
        });
    }

    /**
     * Strip sensitive attributes (declared in the model's $auditExclude array)
     * from a set of values before they are written to the audit log.
     * Password hashes and remember tokens must never be logged.
     */
    public function auditScrub(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        foreach ($this->auditExclude ?? [] as $key) {
            unset($values[$key]);
        }

        return $values;
    }
}
