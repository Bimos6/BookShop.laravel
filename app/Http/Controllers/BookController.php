<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $books = Book::with('author')
            ->when($sort === 'author', function($query) use ($direction) {
                $query->join('authors', 'books.author_id', '=', 'authors.id')
                    ->orderBy('authors.name', $direction)
                    ->select('books.*');
            })
            ->when($sort !== 'author', function($query) use ($sort, $direction) {
                $query->orderBy($sort, $direction);
            })
            ->paginate(8);

        return view('books.index', compact('books'));
    }

    public function create()
    {
        $authors = Author::orderBy('name')->get();
        return view('books.create', compact('authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'title' => 'required|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'genre' => 'required|max:100',
            'pages' => 'required|integer|min:1',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string'
        ]);

        // Создаем или находим автора
        $author = Author::firstOrCreate(['name' => trim($validated['author_name'])]);

        // Обработка обложки
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('book-covers', 'public');
            $validated['cover'] = $path;
        }

        // Создаем книгу
        $book = new Book($validated);
        $book->author_id = $author->id;
        $book->save();

        return redirect()->route('books.index')
            ->with('success', 'Книга успешно добавлена!');
    }

    public function show(Book $book)
    {
        // Явная загрузка автора (хотя обычно работает и без этого)
        $book->load('author');
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $authors = Author::orderBy('name')->get();
        return view('books.edit', compact('book', 'authors'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'title' => 'required|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'genre' => 'required|max:100',
            'pages' => 'required|integer|min:1',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'remove_cover' => 'nullable|boolean'
        ]);

        // Обновляем автора
        $author = Author::firstOrCreate(['name' => trim($validated['author_name'])]);

        // Обработка обложки
        if ($request->has('remove_cover') && $book->cover) {
            Storage::disk('public')->delete($book->cover);
            $validated['cover'] = null;
        }

        if ($request->hasFile('cover')) {
            // Удаляем старую обложку
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $path = $request->file('cover')->store('book-covers', 'public');
            $validated['cover'] = $path;
        }

        $book->update($validated);
        $book->author_id = $author->id;
        $book->save();

        return redirect()->route('books.index')
            ->with('success', 'Книга успешно обновлена!');
    }

    public function destroy(Book $book)
    {
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }
        
        $book->delete();
        
        return redirect()->route('books.index')
            ->with('success', 'Книга удалена!');
    }
}