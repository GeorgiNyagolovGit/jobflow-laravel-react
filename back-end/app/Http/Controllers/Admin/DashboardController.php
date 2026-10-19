<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'stats' => [
                'users' => User::count(),
                'companies' => Company::count(),
                'pendingCompanies' => Company::where('status', 'pending')->count(),
                'jobs' => Job::count(),
                'applications' => JobApplication::count(),
            ],
            'recentJobs' => Job::with('company')
                ->latest()
                ->take(5)
                ->get(),
            'recentUsers' => User::latest()
                ->take(5)
                ->get([
                    'id',
                    'name',
                    'email',
                    'created_at',
                ]),
        ]);
    }
}