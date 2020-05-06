<?php

namespace App\Providers;

use App\Meeting;
use App\Observers\MeetingObserver;
use App\Observers\UserObserver;
use App\User;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Meeting::observe(MeetingObserver::class);
        User::observe(UserObserver::class);
    }
}
