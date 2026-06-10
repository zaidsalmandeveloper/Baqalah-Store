@php
    $badge = match ($status) {
        'success' => 'inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500',
        'reject' => 'inline-flex items-center rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500',
        default => 'inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
    };
    $label = match ($status) {
        'success' => 'Success',
        'reject' => 'Reject',
        default => 'On Progress',
    };
@endphp
<span class="{{ $badge }}">{{ $label }}</span>
