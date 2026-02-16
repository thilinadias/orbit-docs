<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Asset;
use App\Models\AssetType;

class AssetController extends Controller
{
    public function importForm(Request $request) {
        $this->authorize('asset.create');
        $organization = $request->attributes->get('current_organization');
        $preselectedType = $request->query('type');
        
        return view('assets.import', compact('organization', 'preselectedType'));
    }

    public function importProcess(Request $request) {
        $this->authorize('asset.create');
        $organization = $request->attributes->get('current_organization');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        // Normalize header
        $header = array_map(function($h) {
            return strtolower(trim(str_replace(' ', '_', $h)));
        }, $header);

        $imported = 0;
        $errors = [];
        $rowIdx = 1;

        foreach ($data as $row) {
            $rowIdx++;
            if (count($row) != count($header)) {
                $errors[] = "Row $rowIdx: Column count mismatch.";
                continue;
            }

            $rowPayload = array_combine($header, $row);
            
            // Required: Name
            if (empty($rowPayload['name'])) {
                 $errors[] = "Row $rowIdx: Name is missing.";
                 continue;
            }

            // Type
            $typeName = $rowPayload['type'] ?? $request->input('default_type');
            if (empty($typeName)) {
                $errors[] = "Row $rowIdx: Asset Type is missing for {$rowPayload['name']}.";
                continue;
            }

            $type = \App\Models\AssetType::where('name', $typeName)->first();
            if (!$type) {
                $errors[] = "Row $rowIdx: Asset Type '$typeName' not found.";
                continue;
            }

            // Create
            try {
                $organization->assets()->create([
                    'name' => $rowPayload['name'],
                    'asset_type_id' => $type->id,
                    'status' => $rowPayload['status'] ?? 'active',
                    'serial_number' => $rowPayload['serial_number'] ?? null,
                    'manufacturer' => $rowPayload['manufacturer'] ?? null,
                    'model' => $rowPayload['model'] ?? null,
                    'ip_address' => $rowPayload['ip_address'] ?? null,
                    'notes' => $rowPayload['notes'] ?? null,
                    // defaults
                    'monitoring_enabled' => false,
                    'rmm_agent_installed' => false,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row $rowIdx: Failed to create - " . $e->getMessage();
            }
        }

        $msg = "Imported $imported assets.";
        if (count($errors) > 0) {
            $msg .= " With errors: " . count($errors);
            return redirect()->route('assets.index', $organization->slug)
                             ->with('success', $msg)
                             ->with('import_errors', $errors);
        }

        return redirect()->route('assets.index', $organization->slug)->with('success', $msg);
    }

    public function export(Request $request)
    {
        $this->authorize('asset.view');
        $organization = $request->attributes->get('current_organization');

        $query = $organization->assets()->with(['type', 'site', 'values.field']);

        if ($request->has('type')) {
            $type = $request->input('type');
            if ($type && $type !== 'Site Summary') {
                $query->whereHas('type', function ($q) use ($type) {
                    $q->where('name', $type);
                });
            }
        }

        $assets = $query->latest()->get();

        // Collect all unique custom field names from the retrieved assets
        $customFieldNames = [];
        foreach ($assets as $asset) {
            foreach ($asset->values as $value) {
                if ($value->field) {
                    $customFieldNames[$value->field->name] = true;
                }
            }
        }
        $customHeaders = array_keys($customFieldNames);
        sort($customHeaders);

        $headers = array_merge([
            'ID', 'Name', 'Tag', 'Type', 'Site', 'Status', 'Serial Number', 'Manufacturer', 'Model', 'IP Address', 'Purchase Date', 'Warranty Expiration', 'Notes'
        ], $customHeaders);

        $callback = function() use ($assets, $headers, $customHeaders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($assets as $asset) {
                $row = [
                    $asset->id,
                    $asset->name,
                    $asset->asset_tag,
                    $asset->type->name ?? '',
                    $asset->site->name ?? '',
                    $asset->status,
                    $asset->serial_number,
                    $asset->manufacturer,
                    $asset->model,
                    $asset->ip_address,
                    $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '',
                    $asset->warranty_expiration_date ? $asset->warranty_expiration_date->format('Y-m-d') : '',
                    $asset->notes,
                ];

                // Append custom field values
                foreach ($customHeaders as $header) {
                    $val = $asset->values->first(function($v) use ($header) {
                        return $v->field && $v->field->name === $header;
                    });
                    $row[] = $val ? $val->value : '';
                }

                fputcsv($file, $row);
            }
            fclose($file);
        };

        $fileName = 'assets_export_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload($callback, $fileName, [
            "Content-Type" => "text/csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('asset.view');
        $organization = $request->attributes->get('current_organization');
        
        $query = $organization->assets()->with('type');

        if ($request->has('type')) {
            $type = $request->input('type');
            
            if ($type === 'Site Summary') {
                return view('organization.summary', compact('organization'));
            }

            $query->whereHas('type', function ($q) use ($type) {
                $q->where('name', $type);
            });
        }

        $assets = $query->latest()->paginate(10);
        
        return view('assets.index', compact('organization', 'assets'));
    }

    public function create(Request $request)
    {
        $this->authorize('asset.create');
        $organization = $request->attributes->get('current_organization');
        $types = AssetType::with('fields')->get();
        $sites = $organization->sites;
        
        // ... (rest of method)
        $preselectedType = $request->query('type');
        $selectedTypeId = null;
        
        if ($preselectedType) {
            $typeObj = $types->firstWhere('name', $preselectedType);
            if ($typeObj) {
                $selectedTypeId = $typeObj->id;
            }
        }

        return view('assets.create', compact('organization', 'types', 'sites', 'selectedTypeId'));
    }

    public function store(Request $request)
    {
        $this->authorize('asset.create');
        $organization = $request->attributes->get('current_organization');
        
        // ... (rest of method)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_type_id' => 'required|exists:asset_types,id',
            'site_id' => 'nullable|exists:sites,id',
            'serial_number' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'asset_tag' => 'nullable|string',
            'assigned_to' => 'nullable|string',
            'status' => 'required|in:active,archived,broken',
            'ip_address' => 'nullable|string',
            'mac_address' => 'nullable|string',
            'os_version' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'warranty_expiration_date' => 'nullable|date',
            'end_of_life' => 'nullable|date',
            'monitoring_enabled' => 'boolean', // fixed validation type
            'rmm_agent_installed' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['monitoring_enabled'] = $request->has('monitoring_enabled');
        $validated['rmm_agent_installed'] = $request->has('rmm_agent_installed');

        $asset = $organization->assets()->create($validated);

        if ($request->has('custom_fields')) {
            foreach ($request->input('custom_fields') as $fieldId => $value) {
                if (!empty($value)) {
                    $asset->values()->create([
                        'asset_custom_field_id' => $fieldId,
                        'value' => $value,
                    ]);
                }
            }
        }

        return redirect()->route('assets.show', [$organization->slug, $asset->id])->with('success', 'Asset created.');
    }
    public function edit(Request $request, $organization, Asset $asset)
    {
        $this->authorize('asset.edit');
        $currentOrganization = $request->attributes->get('current_organization');
        
        if($asset->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $types = AssetType::with('fields')->get();
        $sites = $currentOrganization->sites;
        $asset->load('values');
        
        return view('assets.edit', compact('currentOrganization', 'asset', 'types', 'sites'));
    }

    public function update(Request $request, $organization, Asset $asset)
    {
        $this->authorize('asset.edit');
        $currentOrganization = $request->attributes->get('current_organization');
        
        if($asset->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $validated = $request->validate([
            // ... (validation)
            'name' => 'required|string|max:255',
            'asset_type_id' => 'required|exists:asset_types,id',
            'site_id' => 'nullable|exists:sites,id',
            'serial_number' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'asset_tag' => 'nullable|string',
            'assigned_to' => 'nullable|string',
            'status' => 'required|in:active,archived,broken',
            'ip_address' => 'nullable|string',
            'mac_address' => 'nullable|string',
            'os_version' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'warranty_expiration_date' => 'nullable|date',
            'end_of_life' => 'nullable|date',
            'monitoring_enabled' => 'boolean',
            'rmm_agent_installed' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        $validated['monitoring_enabled'] = $request->has('monitoring_enabled');
        $validated['rmm_agent_installed'] = $request->has('rmm_agent_installed');

        $asset->update($validated);

        if ($request->has('custom_fields')) {
            foreach ($request->input('custom_fields') as $fieldId => $value) {
                $asset->values()->updateOrCreate(
                    ['asset_custom_field_id' => $fieldId],
                    ['value' => $value]
                );
            }
        }

        return redirect()->route('assets.show', [$currentOrganization->slug, $asset->id])->with('success', 'Asset updated.');
    }

    public function show(Request $request, $organization, Asset $asset)
    {
        $this->authorize('asset.view');
        $currentOrganization = $request->attributes->get('current_organization');
        
        if($asset->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $asset->load(['type.fields', 'values.field']);
        return view('assets.show', compact('currentOrganization', 'asset'));
    }

    public function destroy(Request $request, $organization, Asset $asset)
    {
        $this->authorize('asset.delete');
        $currentOrganization = $request->attributes->get('current_organization');
        
        if($asset->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $asset->delete();

        return redirect()->route('assets.index', $currentOrganization->slug)->with('success', 'Asset deleted.');
    }
}
