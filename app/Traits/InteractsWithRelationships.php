<?php

namespace App\Traits;

use App\Models\Relationship;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait InteractsWithRelationships
{
    /**
     * Get all relationships where this model is the source.
     */
    public function sourceRelationships(): MorphMany
    {
        return $this->morphMany(Relationship::class, 'source');
    }

    /**
     * Get all relationships where this model is the target.
     */
    public function targetRelationships(): MorphMany
    {
        return $this->morphMany(Relationship::class, 'target');
    }

    /**
     * Get all related models (both as source and target).
     * This is a bit complex in Eloquent, so we often define specific Accessors
     * or use a Service to retrieve "related items".
     * 
     * For now, we provide helper methods to create links.
     */

    public function relateTo($targetModel, $type = 'related')
    {
        return $this->sourceRelationships()->create([
            'organization_id' => $this->organization_id, // Assuming model has org_id
            'target_id' => $targetModel->id,
            'target_type' => get_class($targetModel),
            'type' => $type,
        ]);
    }
}
