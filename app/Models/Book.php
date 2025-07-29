<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Book extends Model
{
    protected $guarded = ['id'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function scopeWhereGenre(Builder $query, string $genre): void
    {
        $query->where('genre', $genre);
    }

    public function scopeOrderByField(Builder $query, ?string $sort, ?string $direction): void
    {
        $sort = in_array($sort, ['title', 'year']) ? $sort : 'id';
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';
        
        $query->orderBy($sort, $direction);
    }

    public static function distinctGenres()
    {
        return self::select('genre')->distinct()->pluck('genre')->filter();
    }
}