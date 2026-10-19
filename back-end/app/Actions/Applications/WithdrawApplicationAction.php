<?php

namespace App\Actions\Applications;

use App\Models\JobApplication;
use Illuminate\Validation\ValidationException;

class WithdrawApplicationAction
{
    public function execute(JobApplication $application, $userId)
    {
        if ((int) $application->user_id !== (int) $userId) {
            abort(403);
        }

        if ($application->status !== 'pending') {
            throw ValidationException::withMessages([
                'application' => ['This application can no longer be withdrawn.'],
            ]);
        }

        $application->status = 'withdrawn';
        $application->save();

        return $application;
    }
}