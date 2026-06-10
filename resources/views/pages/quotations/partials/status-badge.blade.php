@php
    $badgeClass = match ($quotation->status) {
        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
        'reject' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
        default => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
    };
    $label = match ($quotation->status) {
        'success' => 'Success',
        'reject' => 'Reject',
        default => 'Pending',
    };
@endphp

<button type="button"
    class="quotation-status-btn inline-flex cursor-pointer items-center rounded-full px-2.5 py-0.5 text-xs font-medium transition hover:opacity-80 {{ $badgeClass }}"
    data-quotation-id="{{ $quotation->id }}"
    data-quotation-number="{{ $quotation->quotation_number }}"
    data-current-status="{{ $quotation->status }}"
    data-has-invoice="{{ $quotation->invoice ? '1' : '0' }}"
    title="Click to change status">
    {{ $label }}
</button>
