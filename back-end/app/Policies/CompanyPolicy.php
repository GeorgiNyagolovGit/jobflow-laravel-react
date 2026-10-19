<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    public function view(User $user, Company $company): bool
    {
        return $company->status === 'approved'
            || $company->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->hasRole('employer');
    }

    public function update(User $user, Company $company): bool
    {
        return $user->is_active && $company->user_id === $user->id;
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->is_active && $company->user_id === $user->id;
    }
}
