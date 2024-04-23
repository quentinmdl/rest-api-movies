<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\MovieRepository;
use App\Interfaces\MovieRepositoryInterface;

use App\Repositories\CategoryRepository;
use App\Interfaces\CategoryRepositoryInterface;

use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MovieRepositoryInterface::class,MovieRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // JsonResource::withoutWrapping();
    }
}
