<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users()
    {
        // Users assigned this role via organization_user pivot
        // This relationship is a bit complex as it's defined on the pivot table.
        // For distinct roles assigned to users, we can use whereHas on users.
        return $this->belongsToMany(User::class, 'organization_user');
    }
}
