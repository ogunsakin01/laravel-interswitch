<?php

namespace OgunsakinDamilola\Interswitch\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use OgunsakinDamilola\Interswitch\InterswitchServiceProvider;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(EloquentFactory::class)->load($this->baseDir() . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            InterswitchServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom($this->baseDir() . '/database/migrations');
    }


    private function baseDir()
    {
        return str_replace('tests', 'src', __DIR__);
    }
}
