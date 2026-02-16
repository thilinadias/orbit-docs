<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\ActivityLog;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        ActivityLog::create([
            'user_id' => $event->user->id,
            'organization_id' => $event->user->organization_id ?? null,
            'action' => 'login',
            'subject_type' => get_class($event->user),
            'subject_id' => $event->user->id,
            'log_name' => 'authentication',
            'description' => 'User logged in',
            'ip_address' => request()->ip(),
        ]);
    }
}
