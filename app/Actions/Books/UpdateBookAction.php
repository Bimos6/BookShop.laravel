<?php

namespace App\Actions\Books;

use App\Models\Book;
use App\Services\AuthorService;
use App\Services\CoverService;

class UpdateBookAction
{
    public function __construct(
        private AuthorService $authorService,
        private CoverService $coverService
    ) {}

    public function handle(Book $book, array $data): Book
    {
        $author = $this->authorService->firstOrCreate(
            trim($data['author_name'])
        );

        $this->coverService->handleUpdate($book, $data);

        $book->update([
            'title' => $data['title'],
            'year' => $data['year'],
            'genre' => $data['genre'],
            'pages' => $data['pages'],
            'description' => $data['description'] ?? null,
            'cover' => $data['cover'] ?? $book->cover,
            'author_id' => $author->id
        ]);

        return $book->fresh();
    }
}