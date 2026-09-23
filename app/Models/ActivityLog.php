<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'causer_name',
        'causer_role',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Nama pelaku — dari relasi user atau fallback causer_name
     */
    public function getCauserDisplayNameAttribute(): string
    {
        return $this->user?->name ?? $this->causer_name ?? 'Sistem';
    }

    /**
     * Warna badge berdasarkan kategori aksi
     */
    public function getBadgeColorAttribute(): string
    {
        return match (true) {
            str_starts_with($this->action, 'auth.')        => 'blue',
            str_starts_with($this->action, 'borrowing.')   => 'purple',
            str_starts_with($this->action, 'return.')      => 'teal',
            str_starts_with($this->action, 'item.')        => 'orange',
            str_starts_with($this->action, 'category.')    => 'yellow',
            str_starts_with($this->action, 'customer.')    => 'red',
            default                                         => 'gray',
        };
    }
}
