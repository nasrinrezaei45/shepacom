<?php

namespace NasrinRezaei45\Shepacom;

use Illuminate\Support\ServiceProvider;

class ShepacomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/shepacom.php', 'shepacom'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/shepacom.php' => config_path('shepacom.php'),
        ]);
    }
}
