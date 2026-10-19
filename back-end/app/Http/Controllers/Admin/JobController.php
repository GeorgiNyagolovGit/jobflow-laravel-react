<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Job::class);

        return response()->json([
            'jobs' => Job::with('company')
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Job::class);

        return response()->json($this->formOptions());
    }

    public function store(Request $request)
    {
        $this->authorize('create', Job::class);

        $data = $this->validatedData($request);

        $data['slug'] = Str::slug($data['title']).'-'.Str::uuid();

        $job = Job::create($data);

        return response()->json([
            'message' => 'Job created successfully.',
            'job' => $job->load([
                'company',
                'category',
                'jobType',
                'skills',
            ]),
        ], 201);
    }

    public function edit(Job $job)
    {
        $this->authorize('update', $job);

        return response()->json([
            'formOptions' => $this->formOptions(),
            'job' => $job->load([
                'skills',
                'company',
                'category',
                'jobType',
            ]),
        ]);
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $data = $this->validatedData($request);

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

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);

        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully.',
        ]);
    }

    private function formOptions(): array
    {
        return [
            'companies' => Company::query()
                ->orderBy('name')
                ->get(['id', 'name']),

            'categories' => Category::query()
                ->orderBy('name')
                ->get(['id', 'name']),

            'jobTypes' => JobType::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ];
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'company_id' => [
                'required',
                'integer',
                'exists:companies,id',
            ],
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
