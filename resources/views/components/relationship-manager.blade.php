@props(['model', 'showList' => true, 'showForm' => true])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        @if($showList)
        <h3 class="text-lg font-semibold mb-4">Related Items</h3>

        <!-- List Existing Relationships -->
        <div class="space-y-4 mb-6">
            @php
                $relationships = $model->sourceRelationships->merge($model->targetRelationships);
            @endphp

            @if($relationships->isEmpty())
                <p class="text-gray-500 italic">No related items found.</p>
            @else
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($relationships as $rel)
                        @php
                            // Determine which side is the "other" item
                            if ($rel->source_type == get_class($model) && $rel->source_id == $model->id) {
                                $relatedItem = $rel->target;
                                $typeLabel = class_basename($rel->target_type);
                            } else {
                                $relatedItem = $rel->source;
                                $typeLabel = class_basename($rel->source_type);
                            }
                            
                            // Generate link based on type
                            $link = '#';
                            if ($relatedItem instanceof \App\Models\Asset) {
                                $link = route('assets.show', [request()->route('organization'), $relatedItem->id]);
                            } elseif ($relatedItem instanceof \App\Models\Credential) {
                                // Credentials might not have a public show page, or verify route
                                $link = '#'; 
                            } elseif ($relatedItem instanceof \App\Models\Document) {
                                $link = route('documents.show', [request()->route('organization'), $relatedItem->id]);
                            }
                        @endphp
                        <li class="py-3 flex justify-between items-center">
                            <div class="flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-3">
                                    {{ $typeLabel }}
                                </span>
                                <a href="{{ $link }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                    {{ $relatedItem->name ?? $relatedItem->title ?? 'Unknown Item' }}
                                </a>
                                @if($rel->type)
                                    <span class="ml-2 text-xs text-gray-500">({{ $rel->type }})</span>
                                @endif
                            </div>
                            
                            <form method="POST" action="{{ route('relationships.destroy', ['organization' => request()->route('organization'), 'relationship' => $rel->id]) }}" onsubmit="return confirm('Are you sure you want to unlink this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        @endif

        @if($showForm)
        <!-- Add New Relationship Form -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <h4 class="text-md font-medium mb-3">Link New Item</h4>
                <form action="{{ route('relationships.store', request()->route('organization')) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="hidden" name="source_id" value="{{ $model->id }}">
                <input type="hidden" name="source_type" value="{{ get_class($model) == 'App\Models\Asset' ? 'asset' : (get_class($model) == 'App\Models\Credential' ? 'credential' : 'document') }}">
                
                <div class="w-full sm:w-1/4">
                    <select name="target_type" id="target_type_select" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required onchange="updateTargetOptions()">
                        <option value="">Select Type...</option>
                        <option value="asset">Asset</option>
                        <option value="credential">Credential</option>
                        <option value="document">Document</option>
                    </select>
                </div>

                <div class="w-full sm:w-1/2">
                    <select name="target_id" id="target_id_select" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        <option value="">Select Item...</option>
                        <!-- Options will be populated via JS -->
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Link
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

@php
    // Fetch options for relationships
    $currentOrgId = session('organization_id') ?? $model->organization_id;
    
    $availableAssets = \App\Models\Asset::where('organization_id', $currentOrgId)
                        ->where('id', '!=', $model->id)
                        ->get(['id', 'name']);
                        
    $availableCredentials = \App\Models\Credential::where('organization_id', $currentOrgId)
                            ->select('id', 'title as name')
                            ->get();
                            
    $availableDocuments = \App\Models\Document::where('organization_id', $currentOrgId)
                            ->select('id', 'title as name')
                            ->get();
@endphp

<script>
    // Simple inline script to populate options
    const availableItems = {
        asset: @json($availableAssets),
        credential: @json($availableCredentials),
        document: @json($availableDocuments)
    };
    
    function updateTargetOptions() {
        const typeSelect = document.getElementById('target_type_select');
        const idSelect = document.getElementById('target_id_select');
        const selectedType = typeSelect.value;
        
        idSelect.innerHTML = '<option value="">Select Item...</option>';
        
        if (selectedType && availableItems[selectedType]) {
            availableItems[selectedType].forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                idSelect.appendChild(option);
            });
        }
    }
</script>
