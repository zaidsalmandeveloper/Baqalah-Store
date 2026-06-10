@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Company Details" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $company->company_name }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Company Code: {{ $company->company_code }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if ($company->status)
                    <span class="inline-flex items-center rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Active</span>
                @else
                    <span class="inline-flex items-center rounded-full bg-error-50 px-3 py-1 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">Inactive</span>
                @endif
                <a href="{{ route('companies.edit', $company) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Edit
                </a>
                <a href="{{ route('companies.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Back
                </a>
            </div>
        </div>

        <div class="mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 p-5 lg:p-6">
            <h4 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Company Information</h4>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Company Name</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->company_name }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Company Code</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->company_code }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Website</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->website ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Registration Number</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->registration_number ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Tax Number</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->tax_number ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Status</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->status_label }}</p>
                </div>
            </div>
        </div>

        <div class="mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 p-5 lg:p-6">
            <h4 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Contact Information</h4>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Contact Person</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->contact_person ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Designation</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->designation ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Email</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Phone</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->phone ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="border border-gray-200 rounded-2xl dark:border-gray-800 p-5 lg:p-6">
            <h4 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Address</h4>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                <div class="lg:col-span-2">
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Address Line 1</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->address_line1 ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">City</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->city ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">State</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->state ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Postal Code</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->postal_code ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Country</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->country ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
