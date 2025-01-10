<?php

namespace SimPay\Laravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SimPayServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('simpay')
            ->hasConfigFile();

        $this->app->bind('simpay', SimPay::class);
    }
}