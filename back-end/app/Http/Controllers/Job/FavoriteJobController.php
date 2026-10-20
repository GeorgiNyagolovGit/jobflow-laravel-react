<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\FavoriteJob;
use App\Models\Job;
use Illuminate\Http\Request;

class FavoriteJobController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'jobs' => $request->user()
                ->favoriteJobs()
                ->with(['job.company', 'job.category', 'job.jobType'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function store(Request $request, Job $job)
    {
        abort_unless($job->status === 'published', 404);

        FavoriteJob::firstOrCreate([
            'user_id' => $request->user()->id,
            'job_id' => $job->id,
        ]);

        return response()->json([
            'message' => 'Job added to your saved jobs.',
        ], 201);
    }

    public function destroy(Request $request, Job $job)
    {
        FavoriteJob::where('user_id', $request->user()->id)
            ->where('job_id', $job->id)
            ->delete();

        return response()->json([
            'message' => 'Job removed from your saved jobs.',
        ]);
    }
}
