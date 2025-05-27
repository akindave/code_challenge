<?php

namespace App\Providers;

use App\Repositories\Contracts\RequestRepositoryInterface;
use App\Repositories\Eloquent\RequestRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            RequestRepositoryInterface::class,
            RequestRepository::class
        );
    }
}