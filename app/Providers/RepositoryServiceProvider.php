<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\ChecklistRepository;
use App\Repositories\ItemRepository;

use App\Interfaces\ChecklistInterface;
use App\Interfaces\ItemInterface;

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
        $this->app->bind(ItemInterface::class,ItemRepository::class);
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
