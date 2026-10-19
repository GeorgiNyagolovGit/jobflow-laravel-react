<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Company::class);

        $query = Company::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return response()->json([
            'companies' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only('status'),
        ]);
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return response()->json([
            'company' => $company->load(['user', 'jobs']),
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        $data = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'approved', 'rejected']),
            ],
        ]);

        $company->update($data);

        return response()->json([
            'message' => 'Company status updated successfully.',
            'company' => $company->fresh()->load('user'),
        ]);
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully.',
        ]);
    }
}
