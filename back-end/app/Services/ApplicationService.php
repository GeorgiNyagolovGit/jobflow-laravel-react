<?php

namespace App\Services;

use App\Actions\Applications\ApplyForJobAction;
use App\Actions\Applications\WithdrawApplicationAction;
use App\Models\JobApplication;

class ApplicationService
{
    protected $applyForJob;
    protected $withdrawApplication;

    public function __construct(
        ApplyForJobAction $applyForJob,
        WithdrawApplicationAction $withdrawApplication
    ) {
        $this->applyForJob = $applyForJob;
        $this->withdrawApplication = $withdrawApplication;
    }

    public function apply($job, $userId, array $data)
    {
        return $this->applyForJob->execute($job, $userId, $data);
    }

    public function withdraw(JobApplication $application, $userId)
    {
        return $this->withdrawApplication->execute(
            $application,
            $userId
        );
    }

    public function getUserApplications($userId)
    {
        return JobApplication::with('job.company')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);
    }

    public function getJobApplications($jobId)
    {
        return JobApplication::with('user', 'job')
            ->where('job_id', $jobId)
            ->latest()
            ->paginate(10);
    }
}