<?php

namespace BlissJaspis\RajaOngkir\Providers;

use Illuminate\Support\ServiceProvider;

class RajaOngkirServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-rajaongkir-komerce.php',
            'laravel-rajaongkir-komerce'
        );
    }
    
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel-rajaongkir-komerce.php' => config_path('laravel-rajaongkir-komerce.php'),
            ], 'config');
        }
    }
}