<?php

namespace App\Providers;

use App\Contracts\CommunicationServiceContract;
use App\Services\CommunicationService;

use Illuminate\Support\ServiceProvider;

class CommunicationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CommunicationServiceContract::class,CommunicationService::class);

    }
}
