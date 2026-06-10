@php
    $inputClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
    $selectClass = $inputClass . ' appearance-none pr-11';
    $quotation = $quotation ?? null;
    $oldItems = old('items', $quotation?->items?->map(fn ($item) => [
        'product_name' => $item->product_name,
        'quantity' => $item->quantity,
        'price' => $item->price,
    ])->toArray() ?? [['product_name' => '', 'quantity' => 1, 'price' => 0]]);
@endphp

<div x-data="quotationForm({
    items: @js($oldItems),
    taxRate: {{ old('tax_rate', $quotation?->tax_rate ?? 15) }},
    includeTax: '{{ old('include_tax', $quotation?->include_tax ?? false) ? '1' : '0' }}',
})">

    <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
        <div>
            <label for="company_id" class="{{ $labelClass }}">Company <span class="text-error-500">*</span></label>
            <div class="relative z-20 bg-transparent">
                <select name="company_id" id="company_id"
                    class="{{ $selectClass }} @error('company_id') border-error-500 @enderror" required>
                    <option value="">Select Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ old('company_id', $quotation?->company_id) == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }} ({{ $company->company_code }})
                        </option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
            @error('company_id')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="quotation_number" class="{{ $labelClass }}">Quotation ID</label>
            <input type="text" id="quotation_number"
                value="{{ $quotation?->quotation_number ?? ($quotationNumber ?? '') }}"
                class="{{ $inputClass }} cursor-not-allowed bg-gray-50 dark:bg-gray-800" readonly disabled />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Auto-generated unique ID</p>
        </div>

        <div>
            <x-form.date-picker
                id="quotation_date"
                name="quotation_date"
                label="Quotation Date *"
                placeholder="Select quotation date"
                defaultDate="{{ old('quotation_date', $quotation?->quotation_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
            />
            @error('quotation_date')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="status" class="{{ $labelClass }}">Status <span class="text-error-500">*</span></label>
            <div class="relative z-20 bg-transparent">
                <select name="status" id="status" class="{{ $selectClass }} @error('status') border-error-500 @enderror" required>
                    <option value="pending" {{ old('status', $quotation?->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="success" {{ old('status', $quotation?->status) === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="reject" {{ old('status', $quotation?->status) === 'reject' ? 'selected' : '' }}>Reject</option>
                </select>
                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
            @error('status')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="tax_rate" class="{{ $labelClass }}">Tax Rate (%) <span class="text-error-500">*</span></label>
            <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100"
                x-model.number="taxRate" @input="recalculate()"
                value="{{ old('tax_rate', $quotation?->tax_rate ?? 15) }}"
                class="{{ $inputClass }} @error('tax_rate') border-error-500 @enderror" required />
            @error('tax_rate')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>

        <div>
            @include('pages.partials.tax-type-field', ['record' => $quotation, 'labelClass' => $labelClass])
        </div>
    </div>

  <div class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">Quotation Items</h4>
            <button type="button" @click="addItem()"
                class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                + Add Item
            </button>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
            <table class="w-full min-w-[700px] text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase text-gray-500 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Product Name</th>
                        <th class="px-4 py-3 text-left w-28">Quantity</th>
                        <th class="px-4 py-3 text-left w-32">Price</th>
                        <th class="px-4 py-3 text-left w-32">Total</th>
                        <th class="px-4 py-3 text-center w-20">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="px-4 py-3">
                                <input type="text" :name="'items[' + index + '][product_name]'" x-model="item.product_name"
                                    class="{{ $inputClass }}" placeholder="Product name" required />
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity"
                                    @input="recalculate()" min="1" step="1"
                                    class="{{ $inputClass }}" required />
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" :name="'items[' + index + '][price]'" x-model.number="item.price"
                                    @input="recalculate()" min="0" step="0.01"
                                    class="{{ $inputClass }}" required />
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-gray-800 dark:text-white/90" x-text="formatMoney(lineTotal(item))"></span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                    class="inline-flex items-center justify-center rounded-lg bg-error-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-error-600">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        @error('items')<p class="mt-2 text-xs text-error-500">{{ $message }}</p>@enderror
        @error('items.*.product_name')<p class="mt-2 text-xs text-error-500">{{ $message }}</p>@enderror
    </div>

    <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-900/50">
        <h4 class="mb-4 text-base font-semibold text-gray-800 dark:text-white/90">Amount Summary</h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="includeTax === '1' ? 'Subtotal (Excl. Tax)' : 'Subtotal'"></p>
                <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90" x-text="formatMoney(subtotal)"></p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Tax Amount (<span x-text="taxRate"></span>%)</p>
                <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90" x-text="formatMoney(taxAmount)"></p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="includeTax === '1' ? 'Grand Total (Incl. Tax)' : 'Grand Total (Excl. + Tax)'"></p>
                <p class="mt-1 text-lg font-semibold text-brand-500" x-text="formatMoney(grandTotal)"></p>
            </div>
            <div class="rounded-lg border border-brand-200 bg-brand-50 p-4 dark:border-brand-500/30 dark:bg-brand-500/10">
                <p class="text-xs text-brand-600 dark:text-brand-400">Total Amount</p>
                <p class="mt-1 text-xl font-bold text-brand-600 dark:text-brand-400" x-text="formatMoney(grandTotal)"></p>
            </div>
        </div>
        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
            <span x-show="includeTax === '0'">Tax Exclusive: line totals summed, then tax added on top.</span>
            <span x-show="includeTax === '1'">Tax Inclusive: line totals already include tax; tax amount is extracted.</span>
        </p>
    </div>
</div>

@push('scripts')
<script>
function quotationForm(config) {
    return {
        items: config.items.length ? config.items : [{ product_name: '', quantity: 1, price: 0 }],
        taxRate: config.taxRate,
        includeTax: config.includeTax,
        subtotal: 0,
        taxAmount: 0,
        grandTotal: 0,

        init() {
            this.recalculate();
        },

        addItem() {
            this.items.push({ product_name: '', quantity: 1, price: 0 });
            this.recalculate();
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
                this.recalculate();
            }
        },

        lineTotal(item) {
            return (parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0);
        },

        recalculate() {
            const lineSum = this.items.reduce((sum, item) => sum + this.lineTotal(item), 0);
            const rate = parseFloat(this.taxRate) || 0;

            if (this.includeTax === '1' || this.includeTax === true) {
                this.grandTotal = Math.round(lineSum * 100) / 100;
                this.taxAmount = Math.round((this.grandTotal - (this.grandTotal / (1 + (rate / 100)))) * 100) / 100;
                this.subtotal = Math.round((this.grandTotal - this.taxAmount) * 100) / 100;
            } else {
                this.subtotal = Math.round(lineSum * 100) / 100;
                this.taxAmount = Math.round((this.subtotal * (rate / 100)) * 100) / 100;
                this.grandTotal = Math.round((this.subtotal + this.taxAmount) * 100) / 100;
            }
        },

        formatMoney(value) {
            return parseFloat(value || 0).toFixed(2);
        },
    };
}
</script>
@endpush
