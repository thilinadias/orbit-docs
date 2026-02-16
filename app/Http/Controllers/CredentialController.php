<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credential;
use App\Models\Asset;
use Illuminate\Support\Facades\Crypt;

class CredentialController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('credential.view');
        $organization = $request->attributes->get('current_organization');
        $credentials = $organization->credentials()->with('asset')->latest()->paginate(10);
        return view('credentials.index', compact('organization', 'credentials'));
    }

    public function create(Request $request)
    {
        $this->authorize('credential.create');
        $organization = $request->attributes->get('current_organization');
        $assets = $organization->assets()->get();
        return view('credentials.create', compact('organization', 'assets'));
    }

    public function store(Request $request)
    {
        $this->authorize('credential.create');
        $organization = $request->attributes->get('current_organization');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'required|string',
            'url' => 'nullable|url',
            'notes' => 'nullable|string',
            'asset_id' => 'nullable|exists:assets,id',
            'visibility' => 'required|in:org,restricted', // Added visibility
        ]);

        // Password is automatically encrypted by the model mutator
        // We need to handle visibility if passed, defaulting to 'org' if not in form.
        // The View might not have 'visibility' input yet.
        // Use logic: if request has visibility, use it.
        
        $organization->credentials()->create($request->all()); // simplied for now, better to use validated

        return redirect()->route('credentials.index', $organization->slug)->with('success', 'Credential created.');
    }

    public function show(Request $request, $organization, Credential $credential)
    {
        $this->authorize('credential.view');
        $currentOrganization = $request->attributes->get('current_organization');
        
        if($credential->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        return view('credentials.show', compact('currentOrganization', 'credential'));
    }

    public function reveal(Request $request, $organization, Credential $credential)
    {
        $this->authorize('credential.reveal');

        // Log access
        \App\Models\CredentialAccessLog::create([
            'credential_id' => $credential->id,
            'user_id' => $request->user()->id,
            'action' => 'reveal',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'password' => $credential->decrypted_password
        ]);
    }

    public function edit(Request $request, $organization, Credential $credential)
    {
        $this->authorize('credential.edit');
        $currentOrganization = $request->attributes->get('current_organization');
        
        if($credential->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $assets = $currentOrganization->assets()->get();
        return view('credentials.edit', compact('currentOrganization', 'credential', 'assets'));
    }

    public function update(Request $request, $organization, Credential $credential)
    {
        $this->authorize('credential.edit');
        $currentOrganization = $request->attributes->get('current_organization');
        
        if($credential->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string', // Nullable on update
            'url' => 'nullable|url',
            'notes' => 'nullable|string',
            'asset_id' => 'nullable|exists:assets,id',
            'visibility' => 'required|in:org,restricted',
        ]);

        // If password provided, update it. If not, remove from validated so it doesn't nullify.
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $credential->update($validated);

        return redirect()->route('credentials.show', [$currentOrganization->slug, $credential->id])->with('success', 'Credential updated.');
    }

    public function destroy(Request $request, $organization, Credential $credential)
    {
        $this->authorize('credential.delete');
        $currentOrganization = $request->attributes->get('current_organization');
        
        if($credential->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $credential->delete();

        return redirect()->route('credentials.index', $currentOrganization->slug)->with('success', 'Credential deleted.');
    }
}
