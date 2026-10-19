<?php

namespace App\Services;

use App\Actions\Jobs\CreateJobAction;
use App\Actions\Jobs\UpdateJobAction;
use App\Actions\Jobs\DeleteJobAction;
use App\Actions\Jobs\PublishJobAction;
use App\Models\Job;

class JobService
{
    protected $createJob;
    protected $updateJob;
    protected $deleteJob;
    protected $publishJob;

    public function __construct(
        CreateJobAction $createJob,
        UpdateJobAction $updateJob,
        DeleteJobAction $deleteJob,
        PublishJobAction $publishJob
    ) {
        $this->createJob = $createJob;
        $this->updateJob = $updateJob;
        $this->deleteJob = $deleteJob;
        $this->publishJob = $publishJob;
    }

    public function getAll()
    {
        return Job::with(['company', 'category', 'jobType', 'skills'])
            ->where('status', 'published')
            ->latest()
            ->paginate(10);
    }

    public function getById($id)
    {
        return Job::with(['company', 'category', 'jobType', 'skills'])
            ->findOrFail($id);
    }

    public function create(array $data, $companyId)
    {
        return $this->createJob->execute($data, $companyId);
    }

    public function update(Job $job, array $data)
    {
        return $this->updateJob->execute($job, $data);
    }

    public function delete(Job $job)
    {
        return $this->deleteJob->execute($job);
    }

    public function publish(Job $job)
    {
        return $this->publishJob->execute($job);
    }
}