<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobType;
use App\Repositories\Contracts\JobRepositoryInterface;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $jobs;

    public function __construct(JobRepositoryInterface $jobs)
    {
        $this->jobs = $jobs;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'category_id',
            'job_type_id',
            'location',
            'is_remote',
            'salary_min',
            'salary_max',
            'sort',
            'per_page',
        ]);

        return response()->json([
            'jobs' => $this->jobs->search($filters)->withQueryString(),
            'filters' => $filters,
            'categories' => Category::query()
                ->orderBy('name')
                ->get(['id', 'name']),
            'jobTypes' => JobType::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function show($job)
    {
        $job = $this->jobs->findById($job);

        abort_unless($job->status === 'published', 404);

        return response()->json([
            'job' => $job,
        ]);
    }
}