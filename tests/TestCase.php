<?php
namespace OgunsakinDamilola\Interswitch\Tests;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use OgunsakinDamilola\Interswitch\InterswitchServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(EloquentFactory::class)->load($this->baseDir().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'factories');

    }

    protected function getPackageProviders($app)
    {
        return [
            InterswitchServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once $this->baseDir().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'setup_default_tables_2022.php';

        (new \SetupDefaultTables2022)->up();
    }

    private function baseDir(){
        return str_replace('tests','src',__DIR__);
    }
}
