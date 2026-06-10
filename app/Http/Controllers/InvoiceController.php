<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Company;
use App\Models\Invoice;
use App\Services\ActivityLogService;
use App\Services\InvoiceService;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService,
        protected ActivityLogService $activityLogService
    ) {}

    public function index(): View
    {
        return view('pages.invoices.index', ['title' => 'Invoice Listing']);
    }

    public function data(): JsonResponse
    {
        return $this->invoiceService->getDataTable();
    }

    public function create(): View
    {
        return view('pages.invoices.create', [
            'title' => 'Add Invoice',
            'companies' => Company::where('status', true)->orderBy('company_name')->get(),
            'invoiceNumber' => $this->invoiceService->generateInvoiceNumber(),
        ]);
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $items = $validated['items'];
        unset($validated['items']);

        $invoice = $this->invoiceService->create($validated, $items);

        $this->activityLogService->log(
            'invoice',
            'created',
            'Invoice '.$invoice->invoice_number.' created',
            $invoice->company?->company_name,
            route('invoices.show', $invoice)
        );

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['company', 'items.deliveryChallanItems', 'quotation', 'payments', 'deliveryChallans.items']);

        return view('pages.invoices.show', [
            'title' => 'Invoice Details',
            'invoice' => $invoice,
        ]);
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load(['company', 'items']);

        return view('pages.invoices.edit', [
            'title' => 'Edit Invoice',
            'invoice' => $invoice,
            'companies' => Company::where('status', true)->orderBy('company_name')->get(),
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validated();
        $items = $validated['items'];
        unset($validated['items']);

        $this->invoiceService->update($invoice, $validated, $items);

        $this->activityLogService->log(
            'invoice',
            'updated',
            'Invoice '.$invoice->invoice_number.' updated',
            null,
            route('invoices.show', $invoice)
        );

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $number = $invoice->invoice_number;
        $this->invoiceService->delete($invoice);

        $this->activityLogService->log(
            'invoice',
            'deleted',
            'Invoice '.$number.' deleted'
        );

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function print(Invoice $invoice, SettingService $settingService): View
    {
        $invoice->load(['company', 'items', 'quotation']);

        return view('pages.invoices.print', [
            'title' => 'Invoice '.$invoice->invoice_number,
            'invoice' => $invoice,
            'settings' => $settingService->get(),
        ]);
    }
}
