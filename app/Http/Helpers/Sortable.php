<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;

class Sortable
{
    public static function link($field, $title)
    {
        $request = app(Request::class);
        $direction = $request->get('direction');
        $currentField = $request->get('sort');
        
        if ($currentField == $field && $direction == 'asc') {
            $newDirection = 'desc';
        } else {
            $newDirection = 'asc';
        }
        
        $query = $request->query();
        $query['sort'] = $field;
        $query['direction'] = $newDirection;
        
        $url = $request->url() . '?' . http_build_query($query);
        
        return "<a href=\"{$url}\">{$title}</a>";
    }
}