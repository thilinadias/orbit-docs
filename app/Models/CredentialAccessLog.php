<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CredentialAccessLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function credential()
    {
        return $this->belongsTo(Credential::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
