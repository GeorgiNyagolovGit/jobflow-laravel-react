<?php

namespace App\Services;

use App\Actions\Companies\CreateCompanyAction;
use App\Actions\Companies\UpdateCompanyAction;
use App\Models\Company;

class CompanyService
{
    protected $createCompany;
    protected $updateCompany;

    public function __construct(
        CreateCompanyAction $createCompany,
        UpdateCompanyAction $updateCompany
    ) {
        $this->createCompany = $createCompany;
        $this->updateCompany = $updateCompany;
    }

    public function getAll()
    {
        return Company::where('status', 'approved')
            ->latest()
            ->paginate(10);
    }

    public function getById($id)
    {
        return Company::with('jobs')
            ->where('status', 'approved')
            ->findOrFail($id);
    }

    public function create(array $data, $userId)
    {
        return $this->createCompany->execute($data, $userId);
    }

    public function update(Company $company, array $data)
    {
        return $this->updateCompany->execute($company, $data);
    }

    public function getJobs(Company $company)
    {
        return $company->jobs()
            ->where('status', 'published')
            ->latest()
            ->paginate(10);
    }
}