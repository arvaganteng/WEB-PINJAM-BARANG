<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Catat satu log aktivitas.
     *
     * @param  string       $action       e.g. "borrowing.approved"
     * @param  string       $description  Teks deskriptif singkat
     * @param  object|null  $subject      Model Eloquent terkait (opsional)
     * @param  array        $properties   Data extra dalam JSON (opsional)
     */
    public static function log(
        string $action,
        string $description,
        ?object $subject = null,
        array $properties = []
    ): void {
        try {
            $user = Auth::user();

            ActivityLog::create([
                'user_id'      => $user?->id,
                'causer_name'  => $user?->name,
                'causer_role'  => $user?->role,
                'action'       => $action,
                'description'  => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'   => $subject?->id ?? null,
                'properties'   => !empty($properties) ? $properties : null,
                'ip_address'   => Request::ip(),
                'user_agent'   => Request::userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Jangan biarkan logging error mengganggu proses utama
            \Illuminate\Support\Facades\Log::error('ActivityLog error: ' . $e->getMessage());
        }
    }
}
