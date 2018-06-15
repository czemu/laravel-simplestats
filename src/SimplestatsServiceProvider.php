<?php

namespace Czemu\Simplestats;

use Illuminate\Support\ServiceProvider;

class SimplestatsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/simplestats.php' => config_path('simplestats.php'),
        ], 'config');

        if ( ! class_exists('CreateSimplestatsCountersTable')) {
            $this->publishes([
                __DIR__.'/database/migrations/create_simplestats_counters_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_simplestats_counters_table.php')
            ], 'migrations');
        }

        if ( ! class_exists('CreateSimplestatsCounterDaysTable')) {
            $this->publishes([
                __DIR__.'/database/migrations/create_simplestats_counter_days_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_simplestats_counter_days_table.php')
            ], 'migrations');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('simplestats', function($app) {
            return new \Czemu\Simplestats\Simplestats($app['request'], $app['cookie']);
        });
    }
}
