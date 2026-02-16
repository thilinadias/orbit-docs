<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Organization extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role_id', 'is_primary')
                    ->withTimestamps();
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function credentials()
    {
        return $this->hasMany(Credential::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function incrementRecentActivity()
    {
        $this->touch();
    }

    public function logActivity($action, $subject, $description = null, $properties = null)
    {
        $data = [
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'log_name' => 'organization',
        ];

        // Map specific properties to new/old values for the viewer if available
        if ($properties && isset($properties['old_status'])) {
            $data['old_values'] = ['status' => $properties['old_status']];
        }
        if ($properties && isset($properties['new_status'])) {
            $data['new_values'] = ['status' => $properties['new_status']];
        }

        $this->activityLogs()->create($data);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function suspend()
    {
        $this->update(['status' => 'suspended']);
        
        // Bulk update Sites
        $this->sites()->update(['status' => 'suspended']);
        
        // Bulk update Assets belonging to those Sites
        // We need to find assets where site_id is in this org's sites
        \App\Models\Asset::whereIn('site_id', $this->sites()->select('id'))->update(['status' => 'suspended']);

        // Bulk update Direct Assets (Using explicit query to avoid any relation weirdness)
        \App\Models\Asset::where('organization_id', $this->id)->update(['status' => 'suspended']);

        $this->logActivity('suspended', $this, "Suspended organization {$this->name}", ['old_status' => 'active', 'new_status' => 'suspended']);
    }

    public function activate()
    {
        $this->update(['status' => 'active']);

        // Bulk update Sites
        $this->sites()->update(['status' => 'active']);

        // Bulk update Assets via Sites (Reactivate 'suspended' ones)
        \App\Models\Asset::whereIn('site_id', $this->sites()->select('id'))
            ->where('status', 'suspended')
            ->update(['status' => 'active']);

        // Bulk update Direct Assets
        \App\Models\Asset::where('organization_id', $this->id)
            ->where('status', 'suspended')
            ->update(['status' => 'active']);

        $this->logActivity('activated', $this, "Activated organization {$this->name}", ['old_status' => 'suspended', 'new_status' => 'active']);
    }
}
