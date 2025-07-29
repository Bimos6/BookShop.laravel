<?php 

namespace App\Providers;

use App\Services\AuthorService;
use App\Services\CoverService;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AuthorService::class, fn() => new AuthorService());
        $this->app->singleton(CoverService::class, fn() => new CoverService());
    }
}