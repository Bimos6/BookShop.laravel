<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function rules()
    {
        return [
            'author_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'genre' => 'required|string|max:100',
            'pages' => 'required|integer|min:1',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'remove_cover' => 'nullable|boolean'
        ];
    }
}