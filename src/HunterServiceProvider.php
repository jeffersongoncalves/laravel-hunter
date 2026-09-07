<?php

namespace JeffersonGoncalves\Hunter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HunterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('hunter')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(HunterClient::class);
        $this->app->alias(HunterClient::class, 'hunter');
    }
}
