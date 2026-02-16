<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Add New User') }}
        </h2>
    </x-slot>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <!-- Name -->
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Name</span>
                <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    name="name" value="{{ old('name') }}" required />
                @error('name')
                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
            </label>

            <!-- Email -->
            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Email</span>
                <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    name="email" type="email" value="{{ old('email') }}" required />
                @error('email')
                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
            </label>

            <!-- Status -->
            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Account Status</span>
                <select name="status" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="disabled" {{ old('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                </select>
                @error('status')
                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
            </label>

            <!-- Enforce 2FA -->
            <label class="block mt-4 text-sm">
                <input type="checkbox" name="is_2fa_enforced" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" {{ old('is_2fa_enforced') ? 'checked' : '' }} />
                <span class="ml-2 text-gray-700 dark:text-gray-400">Require Two-Factor Authentication</span>
                <span class="block text-xs text-gray-500 dark:text-gray-400 ml-6">User will be forced to set up 2FA on next login.</span>
            </label>

            <!-- Initial Organization Assignment -->
            <div class="mt-4 p-4 border rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Initial Organization Access</h3>
                
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Primary Organization</span>
                    <select name="primary_organization_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="">None (Global User only)</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ old('primary_organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block mt-2 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Role in Organization</span>
                    <select name="role_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="">Select Role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }} 
                                @if($role->slug === 'super-admin')
                                    (Super Admin - Careful!)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Password</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        name="password" type="password" required />
                    @error('password')
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block mt-2 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Confirm Password</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        name="password_confirmation" type="password" required />
                </label>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('users.index') }}" class="px-4 py-2 mr-2 text-sm text-gray-700 border rounded-lg hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Create User
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
