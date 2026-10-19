<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('admin') ? true : null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Job $job): bool
    {
        if ($job->status === 'published') {
            return true;
        }

        return $user !== null && $this->ownsJob($user, $job);
    }

    public function create(User $user, ?Company $company = null): bool
    {
        return $user->is_active
            && $user->hasRole('employer')
            && $company !== null
            && $company->user_id === $user->id
            && $company->status === 'approved';
    }

    public function update(User $user, Job $job): bool
    {
        return $user->is_active && $this->ownsJob($user, $job);
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->is_active && $this->ownsJob($user, $job);
    }

    public function publish(User $user, Job $job): bool
    {
        return $user->is_active
            && $this->ownsJob($user, $job)
            && $job->company()->where('status', 'approved')->exists();
    }

    private function ownsJob(User $user, Job $job): bool
    {
        return $job->company()->where('user_id', $user->id)->exists();
    }
}
