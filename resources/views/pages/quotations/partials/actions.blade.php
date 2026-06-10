<div class="flex items-center gap-2">
    <a href="{{ route('quotations.show', $quotation) }}"
        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
        View
    </a>
    <a href="{{ route('quotations.edit', $quotation) }}"
        class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
        Edit
    </a>
    <form action="{{ route('quotations.destroy', $quotation) }}" method="POST"
        onsubmit="return confirm('Are you sure you want to delete this quotation?');">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-error-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-error-600">
            Delete
        </button>
    </form>
</div>
