<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use OgunsakinDamilola\Interswitch\InterswitchServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            InterswitchServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
}


