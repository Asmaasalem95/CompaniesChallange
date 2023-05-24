<?php

namespace App\Providers;

use App\Contracts\CompanyServiceInterface;
use App\Services\CompanyService;

use Illuminate\Support\ServiceProvider;

class CompanyServiceProvider extends ServiceProvider
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
        $this->app->bind(CompanyServiceInterface::class,CompanyService::class);

    }
}
