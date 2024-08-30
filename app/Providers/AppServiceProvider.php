<?php

namespace App\Providers;

use App\Services\Verification\VerificationContext;
use App\Services\Verification\VerificationContextInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VerificationContextInterface::class, VerificationContext::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
