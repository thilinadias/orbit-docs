<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InteractsWithRelationships;
use App\Traits\LogsActivity;

class Document extends Model
{
    use HasFactory, InteractsWithRelationships, LogsActivity;

    protected $guarded = [];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }
    
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
