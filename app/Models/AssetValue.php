<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetValue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function field()
    {
        return $this->belongsTo(AssetCustomField::class, 'asset_custom_field_id');
    }
}
