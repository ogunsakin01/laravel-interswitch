<?php
namespace  OgunsakinDamilola\Interswitch;

use Illuminate\Support\ServiceProvider;

class InterswitchServiceProvider extends ServiceProvider {

    public function boot(){
        $config = realpath(__DIR__ . '/config/interswitch.php');

        $this->publishes([
            $config => config_path('interswitch.php')
        ]);
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
