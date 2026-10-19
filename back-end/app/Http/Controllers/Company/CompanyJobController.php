<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyJobController extends Controller
{
    public function index(Request $request)
    {
        $company = $this->company($request);
        $this->authorize('view', $company);

        return response()->json([
            'company' => $company,
            'jobs' => $company->jobs()
                ->with(['category', 'jobType'])
                ->withCount('applications')
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create(Request $request)
    {
        $company = $this->company($request);
        $this->authorize('create', [Job::class, $company]);

        return response()->json($this->formOptions($company));
    }

    public function store(Request $request)
    {
        $company = $this->company($request);
        $this->authorize('create', [Job::class, $company]);

        $data = $this->validatedData($request);

        $data['company_id'] = $company->id;
        $data['slug'] = Str::slug($data['title']).'-'.Str::uuid();
        $data['status'] = 'draft';

        $job = $company->jobs()->create($data);

        return response()->json([
            'message' => 'Job created as a draft.',
            'job' => $job->load([
                'company',
                'category',
                'jobType',
                'skills',
            ]),
        ], 201);
    }

    public function edit(Request $request, $job)
    {
        $job = $this->ownedJob($request, $job);
        $this->authorize('update', $job);

        return response()->json([
            'formOptions' => $this->formOptions($job->company),
            'job' => $job->load('skills'),
        ]);
    }

    public function update(Request $request, $job)
    {
        $job = $this->ownedJob($request, $job);
        $this->authorize('update', $job);

        $data = $this->validatedData($request);

        if ($data['status'] === 'published') {
            $this->authorize('publish', $job);
        }

        $data['slug'] = $job->slug;

        $job->update($data);

        return response()->json([
            'message' => 'Job updated successfully.',
            'job' => $job->fresh()->load([
                'company',
                'category',
                'jobType',
                'skills',
            ]),
        ]);
    }

    public function destroy(Request $request, $job)
    {
        $job = $this->ownedJob($request, $job);
        $this->authorize('delete', $job);

        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully.',
        ]);
    }

    private function company(Request $request)
    {
        return $request->user()->companies()->firstOrFail();
    }

    private function ownedJob(Request $request, $job)
    {
        return Job::whereHas('company', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->findOrFail($job);
    }

    private function formOptions(Company $company)
    {
        return [
            'company' => $company,
            'categories' => Category::query()
                ->orderBy('name')
                ->get(['id', 'name']),
            'jobTypes' => JobType::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ];
    }

    private function validatedData(Request $request)
    {
        return $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'job_type_id' => [
                'required',
                'integer',
                'exists:job_types,id',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
            ],
            'requirements' => [
                'nullable',
                'string',
            ],
            'responsibilities' => [
                'nullable',
                'string',
            ],
            'location' => [
                'nullable',
                'string',
                'max:255',
            ],
            'is_remote' => [
                'required',
                'boolean',
            ],
            'salary_min' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'salary_max' => [
                'nullable',
                'numeric',
                'gte:salary_min',
            ],
            'salary_currency' => [
                'required',
                'string',
                'size:3',
            ],
            'status' => [
                'required',
                Rule::in(['draft', 'published', 'closed']),
            ],
            'published_at' => [
                'nullable',
                'date',
            ],
            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:published_at',
            ],
        ]);
    }
}
