<?php
namespace AgriPlace\Package;

use AgriPlace\Package\Repository\FilePackageRepository;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PackageRepositoryInterface::class, function() {
            return new FilePackageRepository('/tests/Support/status-all-entries');
        });
    }
}
