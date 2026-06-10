<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\ActivityLogService;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService,
        protected ActivityLogService $activityLogService
    ) {}

    public function index(): View
    {
        return view('pages.companies.index', ['title' => 'View Company']);
    }

    public function data(): JsonResponse
    {
        return $this->companyService->getDataTable();
    }

    public function create(): View
    {
        return view('pages.companies.create', ['title' => 'Add Company']);
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $company = $this->companyService->create($request->validated());

        $this->activityLogService->log(
            'company',
            'created',
            'Company '.$company->company_name.' created',
            'Company code: '.$company->company_code,
            route('companies.show', $company)
        );

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function show(Company $company): View
    {
        return view('pages.companies.show', [
            'title' => 'Company Details',
            'company' => $company,
        ]);
    }

    public function edit(Company $company): View
    {
        return view('pages.companies.edit', [
            'title' => 'Edit Company',
            'company' => $company,
        ]);
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->companyService->update($company, $request->validated());

        $this->activityLogService->log(
            'company',
            'updated',
            'Company '.$company->company_name.' updated',
            null,
            route('companies.show', $company)
        );

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $name = $company->company_name;
        $this->companyService->delete($company);

        $this->activityLogService->log(
            'company',
            'deleted',
            'Company '.$name.' deleted'
        );

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
