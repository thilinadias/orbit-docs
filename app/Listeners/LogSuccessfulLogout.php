<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\ActivityLog;

class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        if ($event->user) {
            ActivityLog::create([
                'user_id' => $event->user->id,
                'organization_id' => $event->user->organization_id ?? null,
                'action' => 'logout',
                'subject_type' => get_class($event->user),
                'subject_id' => $event->user->id,
                'log_name' => 'authentication',
                'description' => 'User logged out',
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
