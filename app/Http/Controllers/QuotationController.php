<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Http\Requests\UpdateQuotationStatusRequest;
use App\Models\Company;
use App\Models\Quotation;
use App\Services\QuotationService;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function __construct(
        protected QuotationService $quotationService
    ) {}

    public function index(): View
    {
        return view('pages.quotations.index', ['title' => 'Quotation Listing']);
    }

    public function data(): JsonResponse
    {
        return $this->quotationService->getDataTable();
    }

    public function create(): View
    {
        return view('pages.quotations.create', [
            'title' => 'Add Quotation',
            'companies' => Company::where('status', true)->orderBy('company_name')->get(),
            'quotationNumber' => $this->quotationService->generateQuotationNumber(),
        ]);
    }

    public function store(StoreQuotationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $items = $validated['items'];
        unset($validated['items']);

        $this->quotationService->create($validated, $items);

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation): View
    {
        $quotation->load(['company', 'items', 'invoice']);

        return view('pages.quotations.show', [
            'title' => 'Quotation Details',
            'quotation' => $quotation,
        ]);
    }

    public function edit(Quotation $quotation): View
    {
        $quotation->load(['company', 'items']);

        return view('pages.quotations.edit', [
            'title' => 'Edit Quotation',
            'quotation' => $quotation,
            'companies' => Company::where('status', true)->orderBy('company_name')->get(),
        ]);
    }

    public function update(UpdateQuotationRequest $request, Quotation $quotation): RedirectResponse
    {
        $validated = $request->validated();
        $items = $validated['items'];
        unset($validated['items']);

        $this->quotationService->update($quotation, $validated, $items);

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation updated successfully.');
    }

    public function destroy(Quotation $quotation): RedirectResponse
    {
        $this->quotationService->delete($quotation);

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation deleted successfully.');
    }

    public function updateStatus(UpdateQuotationStatusRequest $request, Quotation $quotation): JsonResponse
    {
        $result = $this->quotationService->updateStatus($quotation, $request->validated('status'));

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'redirect_url' => $result['redirect_url'],
        ]);
    }

    public function print(Quotation $quotation, SettingService $settingService): View
    {
        $quotation->load(['company', 'items']);

        return view('pages.quotations.print', [
            'quotation' => $quotation,
            'settings' => $settingService->get(),
        ]);
    }
}
