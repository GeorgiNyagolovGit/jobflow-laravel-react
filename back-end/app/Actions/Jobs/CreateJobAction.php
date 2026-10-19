<?php

namespace App\Actions\Jobs;

use App\Models\Job;
use Illuminate\Support\Facades\DB;

class CreateJobAction
{
    public function execute(array $data, $companyId)
    {
        return DB::transaction(function () use ($data, $companyId) {
            $skills = isset($data['skills']) ? $data['skills'] : [];

            unset($data['skills']);

            $data['company_id'] = $companyId;
            $data['status'] = 'draft';

            $job = Job::create($data);

            $job->skills()->sync($skills);

            return $job->load([
                'company',
                'category',
                'jobType',
                'skills',
            ]);
        });
    }
}