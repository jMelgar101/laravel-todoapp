<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\ChecklistRepository;
use App\Interfaces\ChecklistInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ChecklistInterface::class, ChecklistRepository::class);
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
