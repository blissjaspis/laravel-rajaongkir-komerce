<?php

namespace BlissJaspis\RajaOngkir\Providers;

use BlissJaspis\RajaOngkir\RajaOngkir;
use Illuminate\Support\ServiceProvider;

class RajaOngkirServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/rajaongkir-komerce.php',
            'rajaongkir-komerce'
        );

        $this->app->singleton(RajaOngkir::class);
    }
    
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/rajaongkir-komerce.php' => config_path('rajaongkir-komerce.php'),
            ], 'config');
        }
    }
}