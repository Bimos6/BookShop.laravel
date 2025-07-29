<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Services\AuthorService;
use App\Services\CoverService;

class CreateBookAction
{
    public function __construct(
        private AuthorService $authorService,
        private CoverService $coverService
    ) {}

    public function handle(array $data): Book
    {
        $author = $this->authorService->firstOrCreate($data['author_name']);
        
        if (isset($data['cover'])) {
            $data['cover'] = $this->coverService->store($data['cover']);
        }

        return Book::create([
            ...$data,
            'author_id' => $author->id
        ]);
    }
}