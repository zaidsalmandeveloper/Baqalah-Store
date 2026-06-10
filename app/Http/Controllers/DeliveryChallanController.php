<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeliveryChallanRequest;
use App\Models\DeliveryChallan;
use App\Models\Invoice;
use App\Services\DeliveryChallanService;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeliveryChallanController extends Controller
{
    public function __construct(
        protected DeliveryChallanService $deliveryChallanService
    ) {}

    public function index(): View
    {
        return view('pages.delivery-challans.index', [
            'title' => 'Delivery Challan Listing',
        ]);
    }

    public function data(): JsonResponse
    {
        return $this->deliveryChallanService->getDataTable();
    }

    public function create(Invoice $invoice): View
    {
        $invoice->load(['company', 'items']);

        return view('pages.delivery-challans.create', [
            'title' => 'Add Delivery Challan',
            'invoice' => $invoice,
            'challanNumber' => $this->deliveryChallanService->generateChallanNumber(),
            'items' => $this->deliveryChallanService->getInvoiceItemsWithDelivery($invoice),
        ]);
    }

    public function store(StoreDeliveryChallanRequest $request, Invoice $invoice): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $items = $validated['items'];
            unset($validated['items']);

            $challan = $this->deliveryChallanService->create($invoice, $validated, $items);

            return redirect()
                ->route('delivery-challans.show', $challan)
                ->with('success', 'Delivery challan created successfully.');
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['items' => $e->getMessage()]);
        }
    }

    public function show(DeliveryChallan $deliveryChallan): View
    {
        $deliveryChallan->load(['items', 'invoice.company', 'company']);

        return view('pages.delivery-challans.show', [
            'title' => 'Delivery Challan Details',
            'challan' => $deliveryChallan,
        ]);
    }

    public function print(DeliveryChallan $deliveryChallan, SettingService $settingService): View
    {
        $deliveryChallan->load(['items', 'invoice', 'company']);

        return view('pages.delivery-challans.print', [
            'title' => 'Delivery Challan '.$deliveryChallan->challan_number,
            'challan' => $deliveryChallan,
            'invoice' => $deliveryChallan->invoice,
            'company' => $deliveryChallan->company,
            'settings' => $settingService->get(),
        ]);
    }
}
