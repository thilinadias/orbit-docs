<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCustomField extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    public function values()
    {
        return $this->hasMany(AssetValue::class);
    }
}
