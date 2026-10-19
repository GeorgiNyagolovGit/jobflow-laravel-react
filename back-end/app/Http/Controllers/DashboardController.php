<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Models\Job;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->roles()->where('name', 'admin')->exists()) {
            return app(AdminDashboardController::class)();
        }

        if ($user->roles()->where('name', 'employer')->exists()) {
            $company = $user->companies()->first();

            return response()->json([
                'company' => $company,
                'stats' => [
                    'jobs' => $company
                        ? $company->jobs()->count()
                        : 0,

                    'applications' => $company
                        ? Job::where('company_id', $company->id)
                            ->withCount('applications')
                            ->get()
                            ->sum('applications_count')
                        : 0,
                ],
                'recentJobs' => $company
                    ? $company->jobs()->latest()->take(5)->get()
                    : collect(),
            ]);
        }

        return app(UserDashboardController::class)($request);
    }
}