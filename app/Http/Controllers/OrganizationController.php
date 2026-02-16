<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_super_admin) {
            $organizations = Organization::latest()->get();
        } else {
            $organizations = $user->organizations()->latest()->get();
        }
        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        // Only allow users with 'organization.manage' permission to create
        // Super Admins bypass this via Gate::before or implicit check if we used 'can'
        if (!auth()->user()->can('organization.manage') && !auth()->user()->is_super_admin) {
             abort(403, 'You do not have permission to create organizations.');
        }

        return view('organizations.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('organization.manage') && !auth()->user()->is_super_admin) {
             abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:organizations',
            'parent_id' => 'nullable|exists:organizations,id',
            'logo' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('organizations', 'public');
        }

        $organization = Organization::create($validated);
        
        // Attach the creator as an admin/user of the organization
        auth()->user()->organizations()->attach($organization->id, ['role_id' => 1]); // Assuming 1 is Admin, hardcoding for now or need to query Role

        // Redirect to that organization's dashboard
        return redirect()->route('dashboard', $organization->slug)->with('success', 'Organization created.');
    }

    public function edit(Organization $organization)
    {
        // Add authorization check
        abort_unless(
            auth()->user()->is_super_admin || 
            (auth()->user()->organizations->contains($organization) && auth()->user()->can('organization.manage')), 
            403
        );
        
        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        abort_unless(
            auth()->user()->is_super_admin || 
            (auth()->user()->organizations->contains($organization) && auth()->user()->can('organization.manage')), 
            403
        );

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name,' . $organization->id,
            'parent_id' => 'nullable|exists:organizations,id',
            'logo' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('organizations', 'public');
        } else {
            unset($validated['logo']);
        }
        
        $organization->update($validated);

        return redirect()->route('organizations.index')->with('success', 'Organization updated.');
    }

    public function updateNotes(Request $request, Organization $organization)
    {
        abort_unless(
            auth()->user()->is_super_admin || 
            (auth()->user()->organizations->contains($organization) && auth()->user()->can('organization.manage')), 
            403
        );

        $organization->update([
            'notes' => $request->input('notes')
        ]);

        return back()->with('success', 'Notes updated.');
    }

    public function destroy(Organization $organization)
    {
        abort_unless(auth()->user()->is_super_admin, 403); // Only Super Admin should delete orgs typically, or strict manage
        
        // Implement soft delete or check for dependencies? 
        // For now simple delete
        $organization->delete();
        return redirect()->route('organizations.index')->with('success', 'Organization deleted.');
    }

    public function suspend(Organization $organization)
    {
        abort_unless(auth()->user()->is_super_admin, 403); // Only Super Admin for now? Or Admin? Let's check permissions. 
        // Using manage for now but let's stick to previous logical flow, just adding manage check.
        // Actually suspension might be higher level. Let's keep it consistent with update for now but SuperAdmin specific for suspension is safer if desired. 
        // User request didn't specify suspension, just "edit". Let's apply manage check.
       
        abort_unless(
            auth()->user()->is_super_admin || 
            (auth()->user()->organizations->contains($organization) && auth()->user()->can('organization.manage')), 
            403
        );
        
        $organization->suspend();

        return back()->with('success', 'Organization suspended successfully.');
    }

    public function activate(Organization $organization)
    {
        abort_unless(
            auth()->user()->is_super_admin || 
            (auth()->user()->organizations->contains($organization) && auth()->user()->can('organization.manage')), 
            403
        );
        
        $organization->activate();

        return back()->with('success', 'Organization activated successfully.');
    }
}
