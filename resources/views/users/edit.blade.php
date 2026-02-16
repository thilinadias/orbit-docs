<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit User: ') . $user->name }}
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="px-4 py-3 mb-8 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-100">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="px-4 py-3 mb-8 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-100">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="px-4 py-3 mb-8 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-100">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <!-- User Details -->
        <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                User Details
            </h4>
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Name</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        name="name" value="{{ old('name', $user->name) }}" required />
                </label>

                <!-- Email -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Email</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        name="email" type="email" value="{{ old('email', $user->email) }}" required />
                </label>

                <!-- Status -->
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Account Status</span>
                    <select name="status" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="disabled" {{ old('status', $user->status) == 'disabled' ? 'selected' : '' }}>Disabled</option>
                    </select>
                </label>

                <!-- Enforce 2FA -->
                <label class="block mt-4 text-sm">
                    <input type="checkbox" name="is_2fa_enforced" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" {{ old('is_2fa_enforced', $user->is_2fa_enforced) ? 'checked' : '' }} />
                    <span class="ml-2 text-gray-700 dark:text-gray-400">Require Two-Factor Authentication</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 ml-6">{{ __('User will be forced to set up 2FA on next login (if not already enabled).') }}</span>
                </label>

                <!-- Reset 2FA (Only if enabled) -->
                @if($user->hasTwoFactorEnabled())
                <label class="block mt-4 text-sm">
                    <input type="checkbox" name="reset_2fa" class="text-red-600 form-checkbox focus:border-red-400 focus:outline-none focus:shadow-outline-red dark:focus:shadow-outline-gray" />
                    <span class="ml-2 text-red-700 dark:text-red-400">{{ __('Reset Two-Factor Authentication') }}</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 ml-6">{{ __('This will inspect the user\'s current 2FA secret. They will need to set it up again.') }}</span>
                </label>
                @endif

                <!-- Password -->
                 <div class="mt-4 p-2 border-t dark:border-gray-600">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Leave blank to keep current password</span>
                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">New Password</span>
                        <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            name="password" type="password" />
                    </label>
                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Confirm Password</span>
                        <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            name="password_confirmation" type="password" />
                    </label>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Update User
                    </button>
                </div>
            </form>
        </div>

        <!-- Organization Access -->
        <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                Organization Access
            </h4>
            
            <!-- List Assigned Orgs -->
            <div class="mb-6">
                @if($user->organizations->count() > 0)
                <ul class="divide-y dark:divide-gray-700">
                    @foreach($user->organizations as $org)
                    <li class="flex items-center justify-between py-2">
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm text-gray-700 dark:text-gray-300">{{ $org->name }}</span>
                            <span class="text-xs text-gray-500">
                                Role: 
                                <span class="font-bold text-purple-600 dark:text-purple-400">
                                    {{ \App\Models\Role::find($org->pivot->role_id)?->name ?? ($org->pivot->permissions ? 'Custom' : 'None') }}
                                </span>
                            </span>
                        </div>
                        <form action="{{ route('users.organizations.detach', [$user->id, $org->id]) }}" method="POST" onsubmit="return confirm('Remove access to {{ $org->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-lg dark:bg-red-700 dark:text-red-100" aria-label="Remove">
                                Remove
                            </button>
                        </form>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No organizations assigned.</p>
                @endif
            </div>

            <!-- Add Organization Form -->
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <h5 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Grant Access</h5>
                <form id="assign-org-form" action="{{ route('users.organizations.attach', $user->id) }}" method="POST">
                    @csrf
                    <div class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400 font-semibold">Select Organization(s)</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 block mb-2">Check one or more organizations</span>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-gray-800">
                            @foreach($organizations as $org)
                                <label class="flex items-center space-x-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 px-2 rounded cursor-pointer">
                                    <input type="checkbox" name="organization_ids[]" value="{{ $org->id }}" class="form-checkbox text-purple-600 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $org->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('organization_ids')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Role</span>
                        <select name="role_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                            <option value="custom">Custom (Select Permissions)</option>
                        </select>
                    </label>

                    <!-- Custom Permissions Matrix -->
                    <div id="custom-permissions-container" class="mt-4 hidden p-4 border rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                        <h5 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Custom Permissions</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="mb-2">
                                    <h6 class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">{{ ucfirst($module) }}</h6>
                                    @foreach($modulePermissions as $perm)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->slug }}" class="form-checkbox text-purple-600 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $perm->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Assign Access
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.querySelector('select[name="role_id"]');
            const permContainer = document.getElementById('custom-permissions-container');
            const orgCheckboxes = document.querySelectorAll('input[name="organization_ids[]"]');
            const form = document.getElementById('assign-org-form');
            
            function togglePermissions() {
                if (roleSelect.value === 'custom') {
                    permContainer.classList.remove('hidden');
                } else {
                    permContainer.classList.add('hidden');
                }
            }
            
            if(roleSelect) {
                roleSelect.addEventListener('change', togglePermissions);
                togglePermissions(); // Run on load
            }
            
            // Debug: Log selected organizations
            if(orgCheckboxes.length > 0) {
                orgCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const selected = Array.from(orgCheckboxes)
                            .filter(cb => cb.checked)
                            .map(cb => cb.value);
                        console.log('Selected organizations:', selected);
                    });
                });
            }
            
            // Debug: Log form submission
            if(form) {
                form.addEventListener('submit', function(e) {
                    const selected = Array.from(orgCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);
                    console.log('Form submitting with organizations:', selected);
                    if(roleSelect) console.log('Role:', roleSelect.value);
                });
            }
        });
    </script>
</x-app-layout>
