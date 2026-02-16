<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('document.view');
        $organization = $request->attributes->get('current_organization');
        $documents = $organization->documents()->latest()->paginate(10);
        return view('documents.index', compact('organization', 'documents'));
    }

    public function create(Request $request)
    {
        $this->authorize('document.create');
        $organization = $request->attributes->get('current_organization');
        return view('documents.create', compact('organization'));
    }

    public function store(Request $request)
    {
        $this->authorize('document.create');
        $organization = $request->attributes->get('current_organization');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $document = $organization->documents()->create($validated);

        return redirect()->route('documents.show', [$organization->slug, $document->id])->with('success', 'Document created.');
    }

    public function show(Request $request, $organization, Document $document)
    {
        $this->authorize('document.view');
        $currentOrganization = $request->attributes->get('current_organization');
        $htmlContent = Str::markdown($document->content);
        return view('documents.show', compact('currentOrganization', 'document', 'htmlContent'));
    }

    public function edit(Request $request, $organization, Document $document)
    {
        $this->authorize('document.edit');
        return view('documents.edit', compact('organization', 'document'));
    }

    public function update(Request $request, $organization, Document $document)
    {
        $this->authorize('document.edit');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        // Save version before update
        $document->versions()->create([
            'content' => $document->content,
            'user_id' => auth()->id(),
        ]);

        $document->update($validated);

        return redirect()->route('documents.show', [$organization, $document->id])->with('success', 'Document updated.');
    }
}
