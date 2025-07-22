<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'author_id', 'title', 'year', 'genre', 'cover', 'pages', 'description'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}