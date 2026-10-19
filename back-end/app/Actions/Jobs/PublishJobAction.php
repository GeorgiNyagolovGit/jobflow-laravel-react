<?php

namespace App\Actions\Jobs;

use App\Models\Job;

class PublishJobAction
{
    public function execute(Job $job)
    {
        $job->status = 'pending';
        $job->save();

        return $job;
    }
}