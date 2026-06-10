<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoicePaymentRequest;
use App\Models\Company;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function index(): View
    {
        return view('pages.payments.index', [
            'title' => 'Payments',
            'companies' => $this->paymentService->getCompanySummaries(),
        ]);
    }

    public function company(Company $company): View
    {
        $detail = $this->paymentService->getCompanyDetail($company);

        return view('pages.payments.company', [
            'title' => 'Payments - '.$company->company_name,
            ...$detail,
        ]);
    }

    public function storePayment(StoreInvoicePaymentRequest $request, Invoice $invoice): JsonResponse|RedirectResponse
    {
        try {
            $payment = $this->paymentService->recordPayment(
                $invoice,
                $request->validated(),
                $request->file('receipt_image')
            );

            $invoice->refresh();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment recorded successfully.',
                    'payment' => $payment,
                    'invoice' => [
                        'outstanding_amount' => number_format((float) $invoice->outstanding_amount, 2),
                        'payment_status' => $invoice->payment_status,
                        'payment_status_label' => $invoice->payment_status_label,
                    ],
                ]);
            }

            return back()->with('success', 'Payment recorded successfully.');
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['amount' => $e->getMessage()]);
        }
    }
}
