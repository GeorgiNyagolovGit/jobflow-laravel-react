<?php

namespace App\Actions\Jobs;

use App\Models\Job;
use Illuminate\Support\Facades\DB;

class UpdateJobAction
{
    public function execute(Job $job, array $data)
    {
        return DB::transaction(function () use ($job, $data) {
            if (isset($data['skills'])) {
                $job->skills()->sync($data['skills']);
                unset($data['skills']);
            }

            $job->update($data);

            return $job->fresh([
                'company',
                'category',
                'jobType',
                'skills',
            ]);
        });
    }
}