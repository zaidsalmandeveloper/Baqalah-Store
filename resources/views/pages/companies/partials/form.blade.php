@php
    $inputClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
    $selectClass = $inputClass . ' appearance-none pr-11';
    $company = $company ?? null;
@endphp

<div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
    <div>
        <label for="company_name" class="{{ $labelClass }}">Company Name <span class="text-error-500">*</span></label>
        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $company?->company_name) }}"
            class="{{ $inputClass }} @error('company_name') border-error-500 @enderror" placeholder="Enter company name" required />
        @error('company_name')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="company_code" class="{{ $labelClass }}">Company Code <span class="text-error-500">*</span></label>
        <input type="text" name="company_code" id="company_code" value="{{ old('company_code', $company?->company_code) }}"
            class="{{ $inputClass }} @error('company_code') border-error-500 @enderror" placeholder="Enter company code" required />
        @error('company_code')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="website" class="{{ $labelClass }}">Website</label>
        <input type="text" name="website" id="website" value="{{ old('website', $company?->website) }}"
            class="{{ $inputClass }} @error('website') border-error-500 @enderror" placeholder="https://example.com" />
        @error('website')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="registration_number" class="{{ $labelClass }}">Registration Number</label>
        <input type="text" name="registration_number" id="registration_number"
            value="{{ old('registration_number', $company?->registration_number) }}"
            class="{{ $inputClass }} @error('registration_number') border-error-500 @enderror" placeholder="Enter registration number" />
        @error('registration_number')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tax_number" class="{{ $labelClass }}">Tax Number</label>
        <input type="text" name="tax_number" id="tax_number" value="{{ old('tax_number', $company?->tax_number) }}"
            class="{{ $inputClass }} @error('tax_number') border-error-500 @enderror" placeholder="Enter tax number" />
        @error('tax_number')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="contact_person" class="{{ $labelClass }}">Contact Person</label>
        <input type="text" name="contact_person" id="contact_person"
            value="{{ old('contact_person', $company?->contact_person) }}"
            class="{{ $inputClass }} @error('contact_person') border-error-500 @enderror" placeholder="Enter contact person" />
        @error('contact_person')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="designation" class="{{ $labelClass }}">Designation</label>
        <input type="text" name="designation" id="designation" value="{{ old('designation', $company?->designation) }}"
            class="{{ $inputClass }} @error('designation') border-error-500 @enderror" placeholder="Enter designation" />
        @error('designation')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="{{ $labelClass }}">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $company?->email) }}"
            class="{{ $inputClass }} @error('email') border-error-500 @enderror" placeholder="info@company.com" />
        @error('email')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="phone" class="{{ $labelClass }}">Phone</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $company?->phone) }}"
            class="{{ $inputClass }} @error('phone') border-error-500 @enderror" placeholder="Enter phone number" />
        @error('phone')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-2">
        <label for="address_line1" class="{{ $labelClass }}">Address Line 1</label>
        <input type="text" name="address_line1" id="address_line1"
            value="{{ old('address_line1', $company?->address_line1) }}"
            class="{{ $inputClass }} @error('address_line1') border-error-500 @enderror" placeholder="Enter address" />
        @error('address_line1')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="city" class="{{ $labelClass }}">City</label>
        <input type="text" name="city" id="city" value="{{ old('city', $company?->city) }}"
            class="{{ $inputClass }} @error('city') border-error-500 @enderror" placeholder="Enter city" />
        @error('city')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="state" class="{{ $labelClass }}">State</label>
        <input type="text" name="state" id="state" value="{{ old('state', $company?->state) }}"
            class="{{ $inputClass }} @error('state') border-error-500 @enderror" placeholder="Enter state" />
        @error('state')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="postal_code" class="{{ $labelClass }}">Postal Code</label>
        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $company?->postal_code) }}"
            class="{{ $inputClass }} @error('postal_code') border-error-500 @enderror" placeholder="Enter postal code" />
        @error('postal_code')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="country" class="{{ $labelClass }}">Country</label>
        <input type="text" name="country" id="country" value="{{ old('country', $company?->country) }}"
            class="{{ $inputClass }} @error('country') border-error-500 @enderror" placeholder="Enter country" />
        @error('country')
            <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="{{ $labelClass }}">Status <span class="text-error-500">*</span></label>
        <div class="relative z-20 bg-transparent">
            <select name="status" id="status"
                class="{{ $selectClass }} @error('status') border-error-500 @enderror" required>
                <option value="1" {{ old('status', $company?->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $company?->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
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
</div>
