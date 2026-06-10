<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
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
        $this->companyService->create($request->validated());

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

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->companyService->delete($company);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
