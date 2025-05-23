<?php

namespace App\Providers;

use App\Domain\TravelRequest\Interfaces\TravelRequestRepositoryInterface;
use App\Domain\TravelRequest\Interfaces\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use App\Infrastructure\Persistence\Eloquent\TravelRequestRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            TravelRequestRepositoryInterface::class,
            TravelRequestRepository::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
