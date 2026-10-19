<?php

namespace App\Actions\Applications;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApplyForJobAction
{
    public function execute(Job $job, $userId, array $data)
    {
        if ($job->status !== 'published') {
            throw ValidationException::withMessages([
                'job' => ['This job is not available for applications.'],
            ]);
        }

        $existingApplication = JobApplication::where('job_id', $job->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingApplication) {
            throw ValidationException::withMessages([
                'job' => ['You have already applied for this job.'],
            ]);
        }

        return DB::transaction(function () use ($job, $userId, $data) {
            return JobApplication::create([
                'job_id' => $job->id,
                'user_id' => $userId,
                'cover_letter' => isset($data['cover_letter'])
                    ? $data['cover_letter']
                    : null,
                'resume_path' => isset($data['resume_path'])
                    ? $data['resume_path']
                    : null,
                'status' => 'pending',
                'applied_at' => now(),
            ]);
        });
    }
}