@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Add Details" />

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <x-common.component-card title="Company Settings" desc="These details appear on printed quotations and invoices.">
            @php
                $inputClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
                $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
            @endphp

            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label for="company_name" class="{{ $labelClass }}">Company Name <span class="text-error-500">*</span></label>
                    <input type="text" name="company_name" id="company_name"
                        value="{{ old('company_name', $setting->company_name) }}"
                        class="{{ $inputClass }} @error('company_name') border-error-500 @enderror"
                        placeholder="Enter company name" required />
                    @error('company_name')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="phone" class="{{ $labelClass }}">Company Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $setting->phone) }}"
                        class="{{ $inputClass }} @error('phone') border-error-500 @enderror" placeholder="Phone number" />
                    @error('phone')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="phone_2" class="{{ $labelClass }}">Company Phone Number 2</label>
                    <input type="text" name="phone_2" id="phone_2" value="{{ old('phone_2', $setting->phone_2) }}"
                        class="{{ $inputClass }} @error('phone_2') border-error-500 @enderror" placeholder="Secondary phone" />
                    @error('phone_2')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="{{ $labelClass }}">Company Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $setting->email) }}"
                        class="{{ $inputClass }} @error('email') border-error-500 @enderror" placeholder="info@company.com" />
                    @error('email')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="ntn_number" class="{{ $labelClass }}">Company NTN Number</label>
                    <input type="text" name="ntn_number" id="ntn_number" value="{{ old('ntn_number', $setting->ntn_number) }}"
                        class="{{ $inputClass }} @error('ntn_number') border-error-500 @enderror" placeholder="NTN number" />
                    @error('ntn_number')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="address" class="{{ $labelClass }}">Company Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="{{ $inputClass }} @error('address') border-error-500 @enderror"
                        placeholder="Enter company address">{{ old('address', $setting->address) }}</textarea>
                    @error('address')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="logo" class="{{ $labelClass }}">Company Logo</label>
                    @if ($setting->logo)
                        <div class="mb-3 flex items-center gap-4">
                            <img src="{{ $setting->logo_url }}" alt="Company Logo" class="h-16 w-auto rounded-lg border border-gray-200 dark:border-gray-700" />
                            <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <input type="checkbox" name="remove_logo" value="1" class="h-4 w-4 rounded border-gray-300 text-brand-500" />
                                Remove current logo
                            </label>
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100 dark:text-gray-400 dark:file:bg-brand-500/10 dark:file:text-brand-400" />
                    @error('logo')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">Save Settings</x-ui.button>
            </div>
        </x-common.component-card>
    </form>
@endsection
