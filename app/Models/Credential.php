<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use App\Traits\InteractsWithRelationships;
use App\Traits\LogsActivity;

class Credential extends Model
{
    use HasFactory, InteractsWithRelationships, LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'expiry_date' => 'date',
        'last_rotated_at' => 'date',
        'auto_rotate' => 'boolean',
    ];

    // Automatically decrypt password on access
    // Or use a custom accessor to keep it encrypted by default
    
    public function getDecryptedPasswordAttribute()
    {
        try {
            return Crypt::decryptString($this->encrypted_password);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['encrypted_password'] = Crypt::encryptString($value);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
