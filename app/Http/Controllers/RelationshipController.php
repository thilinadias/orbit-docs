<?php

namespace App\Http\Controllers;

use App\Models\Relationship;
use App\Models\Asset;
use App\Models\Credential;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelationshipController extends Controller
{
    public function store(Request $request)
    {
        $currentOrganization = $request->attributes->get('current_organization');

        $validated = $request->validate([
            'source_id' => 'required|integer',
            'source_type' => 'required|string',
            'target_id' => 'required|integer',
            'target_type' => 'required|string',
            'type' => 'nullable|string',
        ]);

        $sourceModel = $this->getModel($validated['source_type'], $validated['source_id']);
        
        // Security check: ensure source belongs to current org
        if ($sourceModel->organization_id != $currentOrganization->id) {
            abort(403, 'Source item does not belong to the current organization.');
        }

        Relationship::create([
            'organization_id' => $currentOrganization->id,
            'source_id' => $validated['source_id'],
            'source_type' => $this->getMorphClass($validated['source_type']),
            'target_id' => $validated['target_id'],
            'target_type' => $this->getMorphClass($validated['target_type']),
            'type' => $validated['type'] ?? 'related',
        ]);

        return back()->with('success', 'Relationship created successfully.');
    }

    public function destroy(Request $request, $organization, Relationship $relationship)
    {
        $currentOrganization = $request->attributes->get('current_organization');

        if ($relationship->organization_id != $currentOrganization->id) {
            abort(403, 'Relationship does not belong to the current organization.');
        }

        $relationship->delete();

        return back()->with('success', 'Relationship removed.');
    }

    private function getModel($type, $id)
    {
        $class = $this->getMorphClass($type);
        return $class::findOrFail($id);
    }
    
    private function getMorphClass($type)
    {
        return match ($type) {
            'asset' => Asset::class,
            'credential' => Credential::class,
            'document' => Document::class,
            default => throw new \Exception("Invalid type"),
        };
    }
}
