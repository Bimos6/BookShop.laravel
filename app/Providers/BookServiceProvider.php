<?php

namespace App\Providers;

use App\Services\BookService;
use App\Services\AuthorService;
use App\Services\CoverService;
use App\Actions\Books\CreateBookAction;
use App\Actions\Books\UpdateBookAction;
use App\Actions\Books\DeleteBookAction;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class BookServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(BookService::class, function ($app) {
            return new BookService(
                $app->make(Request::class),
                $app->make(AuthorService::class),
                $app->make(CoverService::class)
            );
        });

        $this->app->bind(CreateBookAction::class, function ($app) {
            return new CreateBookAction(
                $app->make(AuthorService::class),
                $app->make(CoverService::class)
            );
        });

        $this->app->bind(UpdateBookAction::class, function ($app) {
            return new UpdateBookAction(
                $app->make(AuthorService::class),
                $app->make(CoverService::class)
            );
        });

        $this->app->bind(DeleteBookAction::class, function ($app) {
            return new DeleteBookAction(
                $app->make(CoverService::class)
            );
        });
    }

    public function provides(): array
    {
        return [
            BookService::class,
            CreateBookAction::class,
            UpdateBookAction::class,
            DeleteBookAction::class
        ];
    }
}