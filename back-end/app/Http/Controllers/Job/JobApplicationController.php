<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = JobApplication::with(['job.company', 'user'])
            ->whereHas('job.company', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->latest('applied_at')
            ->paginate(15);

        return response()->json([
            'applications' => $applications,
        ]);
    }

    public function store(Request $request, Job $job)
    {
        abort_unless($job->status === 'published', 404);

        $data = $request->validate([
            'cover_letter' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'resume' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],
        ]);

        if (isset($data['resume'])) {
            $data['resume_path'] = $data['resume']->store('resumes', 'private');

            unset($data['resume']);
        }

        $application = JobApplication::updateOrCreate(
            [
                'job_id' => $job->id,
                'user_id' => $request->user()->id,
            ],
            [
                'cover_letter' => isset($data['cover_letter']) ? $data['cover_letter'] : null,
                'resume_path' => isset($data['resume_path']) ? $data['resume_path'] : null,
            ]
        );

        return response()->json([
            'message' => 'Application submitted successfully.',
            'application' => $application->load(['job.company', 'user']),
        ], 201);
    }

    public function update(Request $request, JobApplication $application)
    {
        $this->authorizeEmployer($request, $application);

        $data = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'reviewing', 'accepted', 'rejected']),
            ],
            'employer_notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
        ]);

        $application->update($data);

        return response()->json([
            'message' => 'Application updated successfully.',
            'application' => $application->fresh()->load(['job.company', 'user']),
        ]);
    }

    public function destroy(Request $request, JobApplication $application)
    {
        abort_unless(
            $application->user_id === $request->user()->id,
            403
        );

        $application->delete();

        return response()->json([
            'message' => 'Application withdrawn successfully.',
        ]);
    }

    private function authorizeEmployer(Request $request, JobApplication $application)
    {
        abort_unless(
            $application->job()
                ->whereHas('company', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                })
                ->exists(),
            403
        );
    }
}