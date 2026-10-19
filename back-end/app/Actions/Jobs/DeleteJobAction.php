<?php

namespace App\Actions\Jobs;

use App\Models\Job;

class DeleteJobAction
{
    public function execute(Job $job)
    {
        return $job->delete();
    }
}