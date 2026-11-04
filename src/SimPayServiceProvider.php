<?php

namespace SimPay\Laravel;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use SimPay\Laravel\Notifications\SimPayChannel;
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

    public function packageRegistered(): void
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('simpay', fn () => new SimPayChannel());
        });
    }
}