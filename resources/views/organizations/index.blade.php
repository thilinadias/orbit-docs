<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>Your Organizations (MSPs)</span>
            @if(Auth::user()->is_super_admin || Auth::user()->can('organization.manage'))
            <a href="{{ route('organizations.create') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Create MSP
            </a>
            @endif
        </div>
    </x-slot>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Sites</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($organizations as $org)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <a href="{{ route('dashboard', $org->slug) }}" class="font-bold hover:text-purple-600">
                                    {{ $org->name }}
                                </a>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $org->slug }}
                        </td>
                         <td class="px-4 py-3 text-sm">
                            {{ $org->sites->count() }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4 text-sm">
                                @if(Auth::user()->is_super_admin || Auth::user()->can('organization.manage'))
                                <a href="{{ route('organizations.edit', $org->id) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="4" class="px-4 py-3 text-center text-gray-500">No organizations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
