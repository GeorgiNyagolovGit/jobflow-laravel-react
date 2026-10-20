<?php

use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\CompanyJobController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Job\FavoriteJobController;
use App\Http\Controllers\Job\JobApplicationController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * JSON endpoints consumed by the separate React application in front-end1.
 * They intentionally use the web middleware so Laravel's session and CSRF
 * protection work through the Vite development proxy.
 */
Route::prefix('api')->group(function (): void {
    // Public API
    Route::get('/csrf-cookie', fn () => response()->noContent());

    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/{job}', [JobController::class, 'show'])->whereNumber('job');
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->whereNumber('company');

    // Authentication endpoints consumed by the standalone React application.
    Route::middleware('guest')->group(function (): void {
        Route::post('/login', [LoginController::class, 'store']);
        Route::post('/register', [RegisterController::class, 'store']);
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store']);
        Route::post('/reset-password', [ResetPasswordController::class, 'store']);
    });

    Route::middleware('auth')->group(function (): void {
        Route::get('/user', fn (Request $request) => response()->json([
            'user' => $request->user()->load('roles'),
        ]));
        Route::post('/logout', [LoginController::class, 'destroy']);

        Route::get('/profile', [ProfileController::class, 'edit']);
        Route::patch('/profile', [ProfileController::class, 'update']);
        Route::put('/settings/password', [SettingsController::class, 'updatePassword']);

        Route::get('/my-company', [CompanyController::class, 'mine']);
        Route::post('/companies', [CompanyController::class, 'store']);
        Route::put('/companies/{company}', [CompanyController::class, 'update'])
            ->whereNumber('company');

        Route::get('/favorites', [FavoriteJobController::class, 'index']);
        Route::post('/jobs/{job}/favorite', [FavoriteJobController::class, 'store'])
            ->whereNumber('job');
        Route::delete('/jobs/{job}/favorite', [FavoriteJobController::class, 'destroy'])
            ->whereNumber('job');

        Route::post('/jobs/{job}/applications', [JobApplicationController::class, 'store'])
            ->whereNumber('job');
        Route::get('/applications', [JobApplicationController::class, 'candidateIndex']);
        Route::delete('/applications/{application}', [JobApplicationController::class, 'destroy'])
            ->whereNumber('application');
    });

    Route::middleware(['auth', 'role:employer'])->group(function (): void {
        Route::get('/my-jobs', [CompanyJobController::class, 'index']);
        Route::post('/jobs', [CompanyJobController::class, 'store']);
        Route::put('/jobs/{job}', [CompanyJobController::class, 'update'])
            ->whereNumber('job');
        Route::delete('/jobs/{job}', [CompanyJobController::class, 'destroy'])
            ->whereNumber('job');
        Route::get('/employer/applications', [JobApplicationController::class, 'index']);
        Route::put('/employer/applications/{application}', [JobApplicationController::class, 'update'])
            ->whereNumber('application');
    });

    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function (): void {
        Route::get('/stats', AdminDashboardController::class);
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::put('/users/{user}', [AdminUserController::class, 'update'])
            ->whereNumber('user');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->whereNumber('user');
        Route::get('/jobs', [AdminJobController::class, 'index']);
        Route::delete('/jobs/{job}', [AdminJobController::class, 'destroy'])
            ->whereNumber('job');
        Route::get('/companies', [AdminCompanyController::class, 'index']);
        Route::patch('/companies/{company}', [AdminCompanyController::class, 'update'])
            ->whereNumber('company');
        Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])
            ->whereNumber('company');
    });
});

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'ok',
    ]);
})->name('home');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->whereNumber('job')->name('jobs.show');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])
    ->whereNumber('company')
    ->name('companies.show');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])
        ->whereNumber('company')
        ->name('companies.edit');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])
        ->whereNumber('company')
        ->name('companies.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])
        ->whereNumber('company')
        ->name('companies.destroy');
});

Route::post('/jobs/{job}/applications', [JobApplicationController::class, 'store'])
    ->middleware('auth')
    ->whereNumber('job')
    ->name('jobs.applications.store');
Route::delete('/jobs/{job}/favorite', [FavoriteJobController::class, 'destroy'])
    ->middleware('auth')
    ->whereNumber('job')
    ->name('jobs.favorite.destroy');
Route::post('/jobs/{job}/favorite', [FavoriteJobController::class, 'store'])
    ->middleware('auth')
    ->whereNumber('job')
    ->name('jobs.favorite.store');
Route::delete('/applications/{application}', [JobApplicationController::class, 'destroy'])
    ->middleware('auth')
    ->whereNumber('application')
    ->name('applications.destroy');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])
        ->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/jobs', [AdminJobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [AdminJobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [AdminJobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [AdminJobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [AdminJobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [AdminJobController::class, 'destroy'])->name('jobs.destroy');

        Route::get('/companies', [AdminCompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/{company}', [AdminCompanyController::class, 'show'])
            ->name('companies.show');
        Route::patch('/companies/{company}', [AdminCompanyController::class, 'update'])
            ->name('companies.update');
        Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])
            ->name('companies.destroy');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

Route::middleware(['auth', 'role:employer'])
    ->prefix('employer')
    ->name('employer.')
    ->group(function (): void {
        Route::get('/jobs', [CompanyJobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [CompanyJobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [CompanyJobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [CompanyJobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [CompanyJobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [CompanyJobController::class, 'destroy'])->name('jobs.destroy');
        Route::get('/applications', [JobApplicationController::class, 'index'])
            ->name('applications.index');
        Route::put('/applications/{application}', [JobApplicationController::class, 'update'])
            ->name('applications.update');
    });

Route::middleware(['auth', 'role:candidate'])
    ->prefix('candidate')
    ->name('candidate.')
    ->group(function (): void {
        Route::post('/jobs/{job}/favorite', [FavoriteJobController::class, 'store'])
            ->whereNumber('job')
            ->name('jobs.favorite.store');
        Route::delete('/jobs/{job}/favorite', [FavoriteJobController::class, 'destroy'])
            ->whereNumber('job')
            ->name('jobs.favorite.destroy');
        Route::post('/jobs/{job}/applications', [JobApplicationController::class, 'store'])
            ->whereNumber('job')
            ->name('jobs.applications.store');
        Route::delete('/applications/{application}', [JobApplicationController::class, 'destroy'])
            ->whereNumber('application')
            ->name('applications.destroy');
    });
