<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    protected $companies;

    public function __construct(CompanyRepositoryInterface $companies)
    {
        $this->companies = $companies;
    }

    public function index()
    {
        return response()->json([
            'companies' => $this->companies->getAll(),
        ]);
    }

    public function show($company)
    {
        return response()->json([
            'company' => $this->companies->findById($company),
            'jobs' => $this->companies->getJobs($company),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Company::class);

        return response()->json([
            'message' => 'Create company.',
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Company::class);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'website' => [
                'nullable',
                'url',
                'max:255',
            ],
            'industry' => [
                'nullable',
                'string',
                'max:255',
            ],
            'size' => [
                'nullable',
                'string',
                'max:255',
            ],
            'location' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $data['user_id'] = $request->user()->id;
        $data['slug'] = Str::slug($data['name']).'-'.Str::uuid();
        $data['status'] = 'pending';

        $company = $this->companies->create($data);

        return response()->json([
            'message' => 'Company submitted for approval.',
            'company' => $company,
        ], 201);
    }

    public function edit($company)
    {
        $company = $this->ownedCompany($company);
        $this->authorize('update', $company);

        return response()->json([
            'company' => $company,
        ]);
    }

    public function update(Request $request, $company)
    {
        $company = $this->ownedCompany($company);
        $this->authorize('update', $company);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'website' => [
                'nullable',
                'url',
                'max:255',
            ],
            'industry' => [
                'nullable',
                'string',
                'max:255',
            ],
            'size' => [
                'nullable',
                'string',
                'max:255',
            ],
            'location' => [
                'nullable',
                'string',
                'max:255',
            ],
            'logo' => [
                'nullable',
                'image',
                'max:2048',
            ],
        ]);

        if (isset($data['logo'])) {
            $data['logo'] = $data['logo']->store('company-logos', 'public');
        }

        $data['slug'] = $company->slug;

        $this->companies->update($company->id, $data);

        return response()->json([
            'message' => 'Company information updated successfully.',
            'company' => $company->fresh(),
        ]);
    }

    public function destroy($company)
    {
        $company = $this->ownedCompany($company);
        $this->authorize('delete', $company);

        $this->companies->delete($company->id);

        return response()->json([
            'message' => 'Company deleted successfully.',
        ]);
    }

    private function ownedCompany($company)
    {
        $company = Company::findOrFail($company);

        abort_unless(
            $company->user_id === request()->user()->id,
            403
        );

        return $company;
    }
}
