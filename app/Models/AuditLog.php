<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    // No updated_at — logs are append-only
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'action', 'auditable_type', 'auditable_id',
        'old_values', 'new_values', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // The user who performed the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The single entry point for creating audit records.
     * Captures the current user, IP, and user-agent automatically.
     */
    public static function record(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): void {
        static::create([
            'user_id'        => $userId ?? Auth::id(),
            'action'         => $action,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id'   => $model?->getKey(),
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
        ]);
    }

    /**
     * Deliberately prevent updates and deletes at the model level.
     * This is application-layer tamper protection (Layer 1).
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \RuntimeException('Audit logs are append-only and cannot be modified.');
    }

    public function delete(): bool
    {
        throw new \RuntimeException('Audit logs are append-only and cannot be deleted.');
    }
}
