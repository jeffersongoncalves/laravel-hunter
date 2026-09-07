<?php

namespace Jeffersongoncalves\LaravelHunter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelHunterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-hunter')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
