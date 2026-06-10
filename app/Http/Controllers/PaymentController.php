<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoicePaymentRequest;
use App\Http\Requests\UpdateInvoicePaymentRequest;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Services\ActivityLogService;
use App\Services\PaymentService;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected ActivityLogService $activityLogService
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

            $this->activityLogService->log(
                'payment',
                'payment_recorded',
                'Payment '.$payment->payment_number.' recorded',
                'Amount: '.number_format((float) $payment->amount, 2).' for Invoice '.$invoice->invoice_number,
                route('payments.print', $payment)
            );

            $invoice->refresh();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment recorded successfully.',
                    'payment' => $payment,
                    'receipt_url' => route('payments.print', $payment),
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

    public function updatePayment(UpdateInvoicePaymentRequest $request, InvoicePayment $payment): JsonResponse|RedirectResponse
    {
        $this->paymentService->updatePaymentDate($payment, $request->validated('payment_date'));

        $this->activityLogService->log(
            'payment',
            'updated',
            'Payment '.$payment->payment_number.' date updated',
            null,
            route('payments.print', $payment)
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment date updated successfully.',
            ]);
        }

        return back()->with('success', 'Payment date updated successfully.');
    }

    public function print(InvoicePayment $payment, SettingService $settingService): View
    {
        $payment->load(['invoice', 'company']);

        return view('pages.payments.print', [
            'title' => 'Receipt '.$payment->payment_number,
            'payment' => $payment,
            'invoice' => $payment->invoice,
            'company' => $payment->company,
            'settings' => $settingService->get(),
        ]);
    }
}
