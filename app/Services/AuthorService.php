<?php

namespace App\Services;

use App\Models\Author;

class AuthorService
{
    public function firstOrCreate(string $name): Author
    {
        return Author::firstOrCreate(['name' => trim($name)]);
    }
}