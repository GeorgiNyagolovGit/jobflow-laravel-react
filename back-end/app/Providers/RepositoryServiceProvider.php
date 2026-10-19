<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\JobRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            JobRepositoryInterface::class,
            JobRepository::class
        );

        $this->app->bind(
            CompanyRepositoryInterface::class,
            CompanyRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    public function boot()
    {
    }
}