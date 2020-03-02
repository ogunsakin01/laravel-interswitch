<?php
namespace  OgunsakinDamilola\Interswitch;

use Illuminate\Support\ServiceProvider;

class InterswitchServiceProvider extends ServiceProvider {

    public function boot(){

        $this->publishes([
            __DIR__ . '/config/interswitch.php' => config_path('interswitch.php'),
            __DIR__ . '/resources/views/emails' => resource_path('views/interswitch/emails'),
            __DIR__ . '/resources/views/transactions_log.blade.php' => resource_path('views/interswitch/transactions_log.blade.php')
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/config/interswitch.php', 'interswitch'
        );
        $this->loadMigrationsFrom(__DIR__.'/databases/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Interswitch');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    public function register(){
        $this->app->bind('laravel-interswitch', function () {
            return new Interswitch;
        });
    }

    public function provides()
    {
        return ['laravel-interswitch'];
    }
}
