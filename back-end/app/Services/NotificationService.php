<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobApplication;
use App\Notifications\NewApplicationNotification;
use App\Notifications\JobApprovedNotification;
use App\Notifications\JobRejectedNotification;

class NotificationService
{
    public function notifyNewApplication(JobApplication $application)
    {
        $application->load('job.company.user');

        $company = $application->job->company;

        if ($company && $company->user) {
            $company->user->notify(
                new NewApplicationNotification($application)
            );
        }
    }

    public function notifyJobApproved(Job $job)
    {
        $job->load('company.user');

        if ($job->company && $job->company->user) {
            $job->company->user->notify(
                new JobApprovedNotification($job)
            );
        }
    }

    public function notifyJobRejected(Job $job)
    {
        $job->load('company.user');

        if ($job->company && $job->company->user) {
            $job->company->user->notify(
                new JobRejectedNotification($job)
            );
        }
    }
}