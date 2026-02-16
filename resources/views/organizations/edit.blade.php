<x-app-layout>
    <x-slot name="header">
        Edit Organization
    </x-slot>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('organizations.update', $organization->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Organization Name</span>
                    <input name="name" value="{{ $organization->name }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" required />
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Organization Logo</span>
                    <input type="file" name="logo" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                @if($organization->logo)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $organization->logo) }}" alt="Current Logo" class="h-12 w-auto bg-gray-100 p-1 rounded">
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Parent Organization (Optional)</span>
                    <select name="parent_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="">None (Top Level)</option>
                        @foreach(\App\Models\Organization::where('id', '!=', $organization->id)->get() as $org)
                            <option value="{{ $org->id }}" {{ $organization->parent_id == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Update Organization
                </button>
            </div>
        </form>

        @can('suspend-organization', $organization)
        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
            <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Danger Zone</h4>
            
            @if($organization->isSuspended())
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Suspended</p>
                    <p>This organization is currently suspended.</p>
                </div>

                <div class="flex space-x-4">
                    <form action="{{ route('organizations.activate', $organization->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Activate Organization
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Danger Zone</p>
                    <p>Suspending this organization will also suspend all associated Sites and Assets.</p>
                </div>

                <form action="{{ route('organizations.suspend', $organization->id) }}" method="POST" onsubmit="return confirm('Suspend Organization?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Suspend Organization
                    </button>
                </form>
            @endif
        </div>
        @endcan
    </div>
</x-app-layout>
