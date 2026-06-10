@php
    $inputClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
    $selectClass = $inputClass . ' appearance-none pr-11';
    $user = $user ?? null;
    $showPassword = $showPassword ?? false;
@endphp

<div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
    <div>
        <label for="name" class="{{ $labelClass }}">Full Name <span class="text-error-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $user?->name) }}"
            class="{{ $inputClass }} @error('name') border-error-500 @enderror" placeholder="Enter full name" required />
        @error('name')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="{{ $labelClass }}">Email <span class="text-error-500">*</span></label>
        <input type="email" name="email" id="email" value="{{ old('email', $user?->email) }}"
            class="{{ $inputClass }} @error('email') border-error-500 @enderror" placeholder="user@example.com" required />
        @error('email')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="phone" class="{{ $labelClass }}">Phone</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $user?->phone) }}"
            class="{{ $inputClass }} @error('phone') border-error-500 @enderror" placeholder="Enter phone number" />
        @error('phone')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="{{ $labelClass }}">Status <span class="text-error-500">*</span></label>
        <div class="relative z-20 bg-transparent">
            <select name="status" id="status"
                class="{{ $selectClass }} @error('status') border-error-500 @enderror" required>
                <option value="1" {{ old('status', $user?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $user?->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
        @error('status')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    @if ($showPassword)
        <div>
            <label for="password" class="{{ $labelClass }}">Password <span class="text-error-500">*</span></label>
            <input type="password" name="password" id="password"
                class="{{ $inputClass }} @error('password') border-error-500 @enderror" placeholder="Enter password" required />
            @error('password')
                <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="{{ $labelClass }}">Confirm Password <span class="text-error-500">*</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="{{ $inputClass }}" placeholder="Confirm password" required />
        </div>
    @endif
</div>
