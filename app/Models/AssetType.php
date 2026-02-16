<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function fields()
    {
        return $this->hasMany(AssetCustomField::class);
    }
}
