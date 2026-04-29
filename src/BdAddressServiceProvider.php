<?php

namespace Kejubayer\BdAddress;

use Illuminate\Support\ServiceProvider;

class BdAddressServiceProvider extends ServiceProvider
{
    /**
     * Boot services.
     */
    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD MIGRATIONS
        |--------------------------------------------------------------------------
        */
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        /*
        |--------------------------------------------------------------------------
        | LOAD ROUTES (optional if you add API later)
        |--------------------------------------------------------------------------
        */
        // $this->loadRoutesFrom(__DIR__.'/Routes/api.php');

        /*
        |--------------------------------------------------------------------------
        | PUBLISH MIGRATIONS
        |--------------------------------------------------------------------------
        */
        $this->publishes([
            __DIR__ . '/Database/migrations' => database_path('migrations'),
        ], 'bd-address-migrations');

        /*
        |--------------------------------------------------------------------------
        | PUBLISH SEEDERS
        |--------------------------------------------------------------------------
        */
        $this->publishes([
            __DIR__ . '/Database/seeders' => database_path('seeders'),
        ], 'bd-address-seeders');

        /*
        |--------------------------------------------------------------------------
        | PUBLISH JSON DATA
        |--------------------------------------------------------------------------
        */
        $this->publishes([
            __DIR__ . '/../database/bd_address.json' => database_path('bd_address.json'),
        ], 'bd-address-data');

        /*
        |--------------------------------------------------------------------------
        | PUBLISH CONFIG (optional future)
        |--------------------------------------------------------------------------
        */
        // $this->publishes([
        //     __DIR__.'/Config/bd-address.php' => config_path('bd-address.php'),
        // ], 'bd-address-config');
    }

    /**
     * Register services.
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | MERGE CONFIG (if you add config file later)
        |--------------------------------------------------------------------------
        */
        // $this->mergeConfigFrom(
        //     __DIR__.'/Config/bd-address.php',
        //     'bd-address'
        // );

        /*
        |--------------------------------------------------------------------------
        | LOAD HELPERS
        |--------------------------------------------------------------------------
        */
        $this->loadHelpers();

        /*
        |--------------------------------------------------------------------------
        | REGISTER COMMANDS
        |--------------------------------------------------------------------------
        */
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Kejubayer\BdAddress\Console\InstallCommand::class,
            ]);
        }
    }

    /**
     * Load helper functions.
     */
    protected function loadHelpers()
    {
        $helperFile = __DIR__ . '/Helpers/helpers.php';

        if (file_exists($helperFile)) {
            require_once $helperFile;
        }
    }
}
