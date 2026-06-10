<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class CompanyService
{
    public function getDataTable(): JsonResponse
    {
        return DataTables::of(Company::query())
            ->addColumn('status_badge', function (Company $company) {
                if ($company->status) {
                    return '<span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Active</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">Inactive</span>';
            })
            ->addColumn('action', function (Company $company) {
                return view('pages.companies.partials.actions', compact('company'))->render();
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);

        return $company->fresh();
    }

    public function delete(Company $company): void
    {
        $company->delete();
    }
}
