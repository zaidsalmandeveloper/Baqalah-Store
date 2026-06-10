@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Account Settings" />

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-700 dark:border-error-500/30 dark:bg-error-500/10 dark:text-error-400">
            <p class="font-medium">Please fix the following errors:</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $inputClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
        $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
    @endphp

    <form action="{{ route('account.settings.update') }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf
        @method('PUT')
        <x-common.component-card title="Profile Information" desc="Update your personal details and profile image.">
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label class="{{ $labelClass }}">Profile Image</label>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <div class="overflow-hidden rounded-full h-20 w-20 border border-gray-200 dark:border-gray-700">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover" id="avatar-preview" />
                        </div>
                        <div class="flex-1 space-y-3">
                            <input type="file" name="avatar" id="avatar" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100 dark:text-gray-400 dark:file:bg-brand-500/10 dark:file:text-brand-400"
                                onchange="previewAvatar(this)" />
                            @if ($user->avatar)
                                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                                    Remove current image
                                </label>
                            @endif
                            @error('avatar')
                                <p class="text-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="name" class="{{ $labelClass }}">Full Name <span class="text-error-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="{{ $inputClass }} @error('name') border-error-500 @enderror" required />
                    @error('name')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="{{ $labelClass }}">Email <span class="text-error-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="{{ $inputClass }} @error('email') border-error-500 @enderror" required />
                    @error('email')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="phone" class="{{ $labelClass }}">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                        class="{{ $inputClass }} @error('phone') border-error-500 @enderror" placeholder="Phone number" />
                    @error('phone')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <x-ui.button type="submit" variant="primary">Save Profile</x-ui.button>
            </div>
        </x-common.component-card>
    </form>

    <form action="{{ route('account.password.update') }}" method="POST">
        @csrf
        @method('PUT')
        <x-common.component-card title="Change Password" desc="Update your password to keep your account secure.">
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label for="current_password" class="{{ $labelClass }}">Current Password <span class="text-error-500">*</span></label>
                    <input type="password" name="current_password" id="current_password"
                        class="{{ $inputClass }} @error('current_password') border-error-500 @enderror" required />
                    @error('current_password')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="{{ $labelClass }}">New Password <span class="text-error-500">*</span></label>
                    <input type="password" name="password" id="password"
                        class="{{ $inputClass }} @error('password') border-error-500 @enderror" required />
                    @error('password')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="{{ $labelClass }}">Confirm New Password <span class="text-error-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="{{ $inputClass }}" required />
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <x-ui.button type="submit" variant="primary">Update Password</x-ui.button>
            </div>
        </x-common.component-card>
    </form>
@endsection

@push('scripts')
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
