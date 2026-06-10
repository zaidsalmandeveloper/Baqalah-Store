<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryChallanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::redirect('/signin', '/login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/account/settings', [AccountController::class, 'settings'])->name('account.settings');
    Route::put('/account/settings', [AccountController::class, 'updateProfile'])->name('account.settings.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    Route::get('/companies/data', [CompanyController::class, 'data'])->name('companies.data');
    Route::resource('companies', CompanyController::class)->except(['data']);

    Route::get('/quotations/data', [QuotationController::class, 'data'])->name('quotations.data');
    Route::get('/quotations/{quotation}/print', [QuotationController::class, 'print'])->name('quotations.print');
    Route::patch('/quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.update-status');
    Route::resource('quotations', QuotationController::class)->except(['data']);

    Route::get('/invoices/data', [InvoiceController::class, 'data'])->name('invoices.data');
    Route::get('/invoices/delivery-challans', [DeliveryChallanController::class, 'index'])->name('delivery-challans.index');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('/invoices/{invoice}/delivery-challans/create', [DeliveryChallanController::class, 'create'])->name('invoices.delivery-challans.create');
    Route::post('/invoices/{invoice}/delivery-challans', [DeliveryChallanController::class, 'store'])->name('invoices.delivery-challans.store');
    Route::resource('invoices', InvoiceController::class)->except(['data']);

    Route::get('/delivery-challans/data', [DeliveryChallanController::class, 'data'])->name('delivery-challans.data');
    Route::get('/delivery-challans/{deliveryChallan}/print', [DeliveryChallanController::class, 'print'])->name('delivery-challans.print');
    Route::get('/delivery-challans/{deliveryChallan}', [DeliveryChallanController::class, 'show'])->name('delivery-challans.show');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/companies/{company}', [PaymentController::class, 'company'])->name('payments.company');
    Route::post('/payments/invoices/{invoice}', [PaymentController::class, 'storePayment'])->name('payments.store');
    Route::patch('/payments/{payment}', [PaymentController::class, 'updatePayment'])->name('payments.update');
    Route::get('/payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');

    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::resource('users', UserController::class)->except(['data', 'show']);
});
