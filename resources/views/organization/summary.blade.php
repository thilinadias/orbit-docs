<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Site Summary') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ editing: {{ $organization->notes ? 'false' : 'true' }} }">
                    
                    <!-- View Mode -->
                    <div x-show="!editing">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Site / Organization Notes</h3>
                            @can('organization.manage')
                            <button @click="editing = true" class="px-4 py-2 bg-purple-600 text-white rounded-md text-sm font-medium hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                {{ $organization->notes ? __('Edit Notes') : __('Add Notes') }}
                            </button>
                            @endcan
                        </div>
                        
                        <div class="prose dark:prose-invert max-w-none bg-gray-50 dark:bg-gray-900 p-6 rounded-lg border border-gray-200 dark:border-gray-700 min-h-[200px]">
                            @if($organization->notes)
                                {!! nl2br(e($organization->notes)) !!}
                            @else
                                <span class="text-gray-500 italic">No notes available for this site.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Edit Mode -->
                    <div x-show="editing" x-cloak>
                        <form action="{{ route('organizations.notes.update', $organization->slug) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Edit Notes
                                </label>
                                
                                @can('organization.manage')
                                    <textarea 
                                        name="notes" 
                                        id="notes" 
                                        rows="20" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                        placeholder="Enter site summary, network details, generic passwords, or important contacts here..."
                                    >{{ old('notes', $organization->notes) }}</textarea>
                                    
                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600">
                                            Cancel
                                        </button>
                                        <x-primary-button>
                                            {{ __('Save Notes') }}
                                        </x-primary-button>
                                    </div>
                                @else
                                    <div class="p-4 bg-red-100 text-red-700 rounded-md">
                                        You do not have permission to edit these notes.
                                    </div>
                                @endcan
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
