<?php

namespace JeffersonGoncalves\Hunter\Tests;

use JeffersonGoncalves\Hunter\HunterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            HunterServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('hunter.api_key', 'fake-api-key');
        $app['config']->set('hunter.base_url', 'https://api.hunter.io/v2');
    }
}
