@php
    $includeTaxValue = old('include_tax', $record?->include_tax ?? false);
    $includeTaxValue = filter_var($includeTaxValue, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
@endphp

<div>
    <label class="{{ $labelClass }}">Tax Selection <span class="text-error-500">*</span></label>
    <div class="mt-2 flex flex-wrap items-center gap-6">
        <label class="flex cursor-pointer items-center gap-2.5 text-sm font-medium text-gray-700 dark:text-gray-400">
            <input type="radio" name="include_tax" value="0" x-model="includeTax" @change="recalculate()"
                class="h-4 w-4 border-gray-300 text-brand-500 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900" />
            Exclusive
        </label>
        <label class="flex cursor-pointer items-center gap-2.5 text-sm font-medium text-gray-700 dark:text-gray-400">
            <input type="radio" name="include_tax" value="1" x-model="includeTax" @change="recalculate()"
                class="h-4 w-4 border-gray-300 text-brand-500 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900" />
            Inclusive
        </label>
    </div>
    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
        <span x-show="includeTax === '0'">Exclusive: tax is added on top of line item totals.</span>
        <span x-show="includeTax === '1'">Inclusive: line item prices already include tax.</span>
    </p>
    @error('include_tax')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
</div>
