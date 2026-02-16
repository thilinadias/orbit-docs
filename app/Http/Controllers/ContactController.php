<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Organization;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $organization = $request->attributes->get('current_organization');
        $organization->incrementRecentActivity();
        
        $contacts = $organization->contacts()
            ->orderBy('is_vip', 'desc')
            ->orderBy('last_name')
            ->paginate(10);
            
        return view('contacts.index', compact('organization', 'contacts'));
    }

    public function create(Request $request)
    {
        $organization = $request->attributes->get('current_organization');
        return view('contacts.create', compact('organization'));
    }

    public function store(Request $request)
    {
        $organization = $request->attributes->get('current_organization');
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_mobile' => 'nullable|string|max:20',
            'phone_office' => 'nullable|string|max:20',
            'is_vip' => 'boolean',
            'is_primary' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $contact = $organization->contacts()->create($validated);
        
        $organization->logActivity('created', $contact, $contact->first_name . ' ' . $contact->last_name);

        return redirect()->route('contacts.index', $organization->slug)
            ->with('success', 'Contact created successfully.');
    }

    public function show(Request $request, $organization, Contact $contact)
    {
        $currentOrganization = $request->attributes->get('current_organization');
        if($contact->organization_id !== $currentOrganization->id) {
            abort(404);
        }
        
        return view('contacts.show', ['organization' => $currentOrganization, 'contact' => $contact]);
    }

    public function edit(Request $request, $organization, Contact $contact)
    {
        $currentOrganization = $request->attributes->get('current_organization');
        if($contact->organization_id !== $currentOrganization->id) {
            abort(404);
        }
        return view('contacts.edit', ['organization' => $currentOrganization, 'contact' => $contact]);
    }

    public function update(Request $request, $organization, Contact $contact)
    {
        $currentOrganization = $request->attributes->get('current_organization');
        if($contact->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_mobile' => 'nullable|string|max:20',
            'phone_office' => 'nullable|string|max:20',
            'is_vip' => 'boolean',
            'is_primary' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        $validated['is_vip'] = $request->has('is_vip');
        $validated['is_primary'] = $request->has('is_primary');

        $contact->update($validated);
        
        $currentOrganization->logActivity('updated', $contact, $contact->first_name . ' ' . $contact->last_name);

        return redirect()->route('contacts.index', $currentOrganization->slug)
            ->with('success', 'Contact updated successfully.');
    }

    public function destroy(Request $request, $organization, Contact $contact)
    {
        $currentOrganization = $request->attributes->get('current_organization');
        if($contact->organization_id !== $currentOrganization->id) {
            abort(404);
        }

        $contact->delete();
        $currentOrganization->logActivity('deleted', $contact, $contact->first_name . ' ' . $contact->last_name);

        return redirect()->route('contacts.index', $currentOrganization->slug)
            ->with('success', 'Contact deleted successfully.');
    }
}
