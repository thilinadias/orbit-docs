<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->recordActivity('created');
        });

        static::updated(function ($model) {
            $model->recordActivity('updated');
        });

        static::deleted(function ($model) {
            $model->recordActivity('deleted');
        });
    }

    public function recordActivity($event)
    {
        $logName = strtolower(class_basename($this));

        // Skip sensitive fields if defined in model
        $hidden = $this->getHidden();
        
        $oldValues = [];
        $newValues = [];

        if ($event === 'updated') {
            $oldValues = $this->getOriginal();
            $newValues = $this->getAttributes();
            
            // Only get changed fields
            $changes = $this->getChanges();
            $oldValues = array_intersect_key($oldValues, $changes);
            $newValues = array_intersect_key($newValues, $changes);
        }

        // Mask hidden fields
        foreach ($hidden as $field) {
            if (isset($oldValues[$field])) $oldValues[$field] = '********';
            if (isset($newValues[$field])) $newValues[$field] = '********';
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => (auth()->check() && auth()->user()->organization_id) ? auth()->user()->organization_id : null, // Best effort
            'action' => $event,
            'subject_type' => get_class($this),
            'subject_id' => $this->id,
            'log_name' => $logName,
            'description' => ucfirst($event) . " " . $logName . " #" . $this->id,
            'old_values' => count($oldValues) > 0 ? $oldValues : null,
            'new_values' => count($newValues) > 0 ? $newValues : null,
            'properties' => null, // Can be used for extra metadata
            'ip_address' => request()->ip(),
        ]);
    }
}
