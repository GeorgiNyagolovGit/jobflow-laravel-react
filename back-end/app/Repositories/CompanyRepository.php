<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function getAll($perPage = 10)
    {
        return Company::where('status', 'approved')
            ->latest()
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return Company::with('jobs')
            ->where('status', 'approved')
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Company::create($data);
    }

    public function update($id, array $data)
    {
        $company = Company::findOrFail($id);

        $company->update($data);

        return $company->fresh();
    }

    public function delete($id)
    {
        $company = Company::findOrFail($id);

        return $company->delete();
    }

    public function getJobs($companyId, $perPage = 10)
    {
        $company = Company::findOrFail($companyId);

        return $company->jobs()
            ->where('status', 'published')
            ->latest()
            ->paginate($perPage);
    }
}