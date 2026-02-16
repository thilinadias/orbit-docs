<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Role Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Permission
                                    </th>
                                    @foreach($roles as $role)
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ $role->name }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($permissions as $module => $modulePermissions)
                                    <tr class="bg-gray-50 dark:bg-gray-900">
                                        <td colspan="{{ $roles->count() + 1 }}" class="px-6 py-2 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">
                                            {{ ucfirst($module) }} Module
                                        </td>
                                    </tr>
                                    @foreach($modulePermissions as $permission)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <div class="flex flex-col">
                                                    <span>{{ $permission->name }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">{{ $permission->description }}</span>
                                                </div>
                                            </td>
                                            @foreach($roles as $role)
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <form action="{{ route('roles.update', $role->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <input type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}" 
                                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600"
                                                                   {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                                                                   onchange="this.form.submit()">
                                                        </label>
                                                        
                                                        {{-- Hidden inputs to keep other existing permissions for this role when submitting --}}
                                                        @foreach($role->permissions as $existingPerm)
                                                            @if($existingPerm->id !== $permission->id)
                                                                <input type="hidden" name="permissions[]" value="{{ $existingPerm->id }}">
                                                            @endif
                                                        @endforeach
                                                    </form>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        <p>* Note: Super Admin role has all permissions by default and is not shown here.</p>
                        <p>* Changes are saved automatically when you toggle a checkbox.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
