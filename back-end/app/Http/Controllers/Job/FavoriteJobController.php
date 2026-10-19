<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\FavoriteJob;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteJobController extends Controller
{
    public function store(Request $request, Job $job): RedirectResponse
    {
        abort_unless($job->status === 'published', 404);

        FavoriteJob::firstOrCreate([
            'user_id' => $request->user()->id,
            'job_id' => $job->id,
        ]);

        return back()->with('success', 'Job added to your saved jobs.');
    }

    public function destroy(Request $request, Job $job): RedirectResponse
    {
        FavoriteJob::where('user_id', $request->user()->id)
            ->where('job_id', $job->id)
            ->delete();

        return back()->with('success', 'Job removed from your saved jobs.');
    }
}
