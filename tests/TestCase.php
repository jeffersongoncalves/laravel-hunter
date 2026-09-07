<?php

namespace Jeffersongoncalves\LaravelHunter\Tests;

use Jeffersongoncalves\LaravelHunter\LaravelHunterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelHunterServiceProvider::class,
        ];
    }
}
