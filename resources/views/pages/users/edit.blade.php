@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit User" />

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <x-common.component-card title="Edit User" desc="Update user details below.">
            @include('pages.users.partials.form', ['showPassword' => false])

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">Update User</x-ui.button>
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    Cancel
                </a>
            </div>
        </x-common.component-card>
    </form>
@endsection
