@php
    $inputClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
@endphp

<div x-data="deliveryChallanForm({
    items: @js($items->map(fn ($item) => [
        'invoice_item_id' => $item['id'],
        'product_name' => $item['product_name'],
        'quantity_ordered' => $item['quantity_ordered'],
        'quantity_delivered' => $item['quantity_delivered'],
        'balance_quantity' => $item['balance_quantity'],
        'deliver_now' => 0,
    ])->values()->toArray()),
})">

    <div class="mb-6 grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
        <div>
            <label class="{{ $labelClass }}">Invoice</label>
            <input type="text" value="{{ $invoice->invoice_number }}" class="{{ $inputClass }} cursor-not-allowed bg-gray-50 dark:bg-gray-800" readonly disabled>
        </div>
        <div>
            <label class="{{ $labelClass }}">Challan No.</label>
            <input type="text" value="{{ $challanNumber }}" class="{{ $inputClass }} cursor-not-allowed bg-gray-50 dark:bg-gray-800" readonly disabled>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Auto-generated on save</p>
        </div>
        <div>
            <label class="{{ $labelClass }}">Company</label>
            <input type="text" value="{{ $invoice->company?->company_name ?? '-' }}" class="{{ $inputClass }} cursor-not-allowed bg-gray-50 dark:bg-gray-800" readonly disabled>
        </div>
        <div>
            <x-form.date-picker
                id="delivery_date"
                name="delivery_date"
                label="Delivery Date *"
                placeholder="Select delivery date"
                defaultDate="{{ old('delivery_date', now()->format('Y-m-d')) }}"
            />
            @error('delivery_date')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="received_person_name" class="{{ $labelClass }}">Received Person Name <span class="text-error-500">*</span></label>
            <input type="text" name="received_person_name" id="received_person_name" required
                value="{{ old('received_person_name') }}"
                placeholder="Person who received the delivery"
                class="{{ $inputClass }} @error('received_person_name') border-error-500 @enderror">
            @error('received_person_name')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>
        <div class="lg:col-span-2">
            <label for="received_location" class="{{ $labelClass }}">Received Location / Address <span class="text-error-500">*</span></label>
            <textarea name="received_location" id="received_location" rows="3" required
                placeholder="Delivery address or location"
                class="{{ $inputClass }} min-h-[88px] @error('received_location') border-error-500 @enderror">{{ old('received_location') }}</textarea>
            @error('received_location')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>
    </div>

    @error('items')<p class="mb-4 text-sm text-error-500">{{ $message }}</p>@enderror

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase text-gray-500 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Ordered Qty</th>
                    <th class="px-4 py-3">Already Delivered</th>
                    <th class="px-4 py-3">Balance</th>
                    <th class="px-4 py-3">Deliver Now</th>
                    <th class="px-4 py-3">Remaining</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(item, index) in items" :key="item.invoice_item_id">
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="px-4 py-3" x-text="index + 1"></td>
                        <td class="px-4 py-3 font-medium" x-text="item.product_name"></td>
                        <td class="px-4 py-3" x-text="item.quantity_ordered"></td>
                        <td class="px-4 py-3" x-text="item.quantity_delivered"></td>
                        <td class="px-4 py-3 font-medium text-warning-600" x-text="item.balance_quantity"></td>
                        <td class="px-4 py-3">
                            <input type="hidden" :name="'items[' + index + '][invoice_item_id]'" :value="item.invoice_item_id">
                            <input type="number"
                                :name="'items[' + index + '][quantity_delivered]'"
                                x-model.number="item.deliver_now"
                                min="0"
                                :max="item.balance_quantity"
                                :disabled="item.balance_quantity <= 0"
                                class="h-10 w-24 rounded-lg border border-gray-300 bg-transparent px-3 text-sm focus:border-brand-300 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 disabled:cursor-not-allowed disabled:opacity-50">
                        </td>
                        <td class="px-4 py-3 font-medium text-brand-600" x-text="remaining(item)"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Enter quantity only for items you want to deliver in this challan. Items with zero balance are disabled.</p>
</div>

@push('scripts')
<script>
    function deliveryChallanForm(config) {
        return {
            items: config.items,
            remaining(item) {
                const deliver = parseInt(item.deliver_now) || 0;
                return Math.max(0, item.balance_quantity - deliver);
            },
        };
    }
</script>
@endpush
