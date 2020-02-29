<?php
namespace  OgunsakinDamilola\Interswitch;

use Illuminate\Support\ServiceProvider;
use OgunsakinDamilola\Interswitch\Interswitch;

class InterswitchServiceProvider extends ServiceProvider {

    public function boot(){
        $config = realpath(__DIR__ . '/resources/config/interswitch.php');

        $this->publishes([
            $config => config_path('interswitch.php')
        ]);
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
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
