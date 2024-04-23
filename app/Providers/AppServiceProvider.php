<?php

namespace App\Providers;

use App\Repositories\MovieRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\MovieRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MovieRepositoryInterface::class,MovieRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // JsonResource::withoutWrapping();
    }
}
