<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Services\CoverService;

class DeleteBookAction
{
    public function __construct(private CoverService $coverService) {}

    public function handle(Book $book): void
    {
        $this->coverService->delete($book->cover);
        $book->delete();
    }
}