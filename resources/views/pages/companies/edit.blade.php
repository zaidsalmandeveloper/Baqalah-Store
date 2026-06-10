@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Company" />

    <form action="{{ route('companies.update', $company) }}" method="POST">
        @csrf
        @method('PUT')
        <x-common.component-card title="Edit Company" desc="Update the company details below.">
            @include('pages.companies.partials.form', ['company' => $company])

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">Update Company</x-ui.button>
                <a href="{{ route('companies.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    Cancel
                </a>
            </div>
        </x-common.component-card>
    </form>
@endsection
