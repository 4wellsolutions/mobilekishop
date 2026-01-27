<?php

namespace App\Providers;

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
        // DB query logging disabled for production
        // Uncomment below for debugging:
        // \DB::listen(function ($query) {
        //     \Log::info(
        //         'SQL: ' . $query->sql . ' | Time: ' . $query->time . 'ms',
        //         $query->bindings
        //     );
        // });
    }
}
