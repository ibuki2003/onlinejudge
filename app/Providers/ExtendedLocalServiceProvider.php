<?php
namespace App\Providers;

use Storage;
use Illuminate\Support\ServiceProvider;
use App\Filesystem\Plugins\ZipExtractTo;

class ExtendedLocalServiceProvider extends ServiceProvider 
{
    public function boot()
    {
        Storage::extend('local', function($app, $config) {
            return Storage::createLocalDriver($config)->addPlugin(new ZipExtractTo());
        });
    }
}
