<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\News\NewsRepository;
use App\Repositories\News\Concerns\NewsRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
