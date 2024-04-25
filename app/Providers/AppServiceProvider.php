<?php

namespace App\Providers;

use App\Repositories\MediaRepository;

use App\Repositories\MovieRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\CategoryRepository;
use App\Interfaces\MediaRepositoryInterface;

use App\Interfaces\MovieRepositoryInterface;
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
        $this->app->bind(MediaRepositoryInterface::class,MediaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // JsonResource::withoutWrapping();
    }
}
