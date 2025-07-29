<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Filesystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('admin_mode', fn() => session('admin_mode', false));
        $this->app->singleton('files', function() {
            return new Filesystem();
        });
    }
    public function boot()
    {
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');
    }
}