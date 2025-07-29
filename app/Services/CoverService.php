<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class CoverService
{
    public function store($file): string
    {
        return $file->store('book-covers', 'public');
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function handleUpdate(Book $book, array &$data): void
    {
        if ($data['remove_cover'] ?? false) {
            $this->delete($book->cover);
            $data['cover'] = null;
        }

        if (isset($data['cover'])) {
            $this->delete($book->cover);
            $data['cover'] = $this->store($data['cover']);
        } else {
            $data['cover'] = $book->cover;
        }
    }
}