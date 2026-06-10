<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        return view('pages.dashboard.ecommerce', [
            'title' => 'Dashboard',
            'stats' => $this->dashboardService->getStats(),
            'chart' => $this->dashboardService->getMonthlyInvoiceChart(),
            'paymentChart' => $this->dashboardService->getMonthlyPaymentChart(),
            'recentInvoices' => $this->dashboardService->getRecentInvoices(),
            'recentQuotations' => $this->dashboardService->getRecentQuotations(),
            'recentPayments' => $this->dashboardService->getRecentPayments(),
            'topOutstanding' => $this->dashboardService->getTopOutstandingCompanies(),
        ]);
    }
}
