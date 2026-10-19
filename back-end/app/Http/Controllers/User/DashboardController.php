<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'applications' => $user->applications()
                ->with('job.company')
                ->latest('applied_at')
                ->paginate(10),

            'favoriteJobs' => $user->favoriteJobs()
                ->with('job.company')
                ->latest()
                ->take(6)
                ->get(),
        ]);
    }
}