@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Activity Logs" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">System Activity Logs</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track all actions performed across the ERP.</p>
            </div>
            <form action="{{ route('notifications.mark-read') }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Mark All as Read
                </button>
            </form>
        </div>

        <div class="overflow-x-auto p-4 sm:p-6">
            <table id="activity-logs-table" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Module</th>
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Details</th>
                        <th class="px-4 py-3">Date & Time</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#activity-logs-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('notifications.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user_name', name: 'user.name' },
                    { data: 'module_badge', name: 'module', orderable: false, searchable: false },
                    { data: 'action_badge', name: 'action', orderable: false, searchable: false },
                    { data: 'details', name: 'title', orderable: false },
                    { data: 'logged_at', name: 'created_at' },
                ],
                order: [[0, 'desc']],
            });
        });
    </script>
@endpush
