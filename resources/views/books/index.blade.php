@extends('layouts.app')

@section('title', 'Список книг')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Список книг</h1>
        <a href="{{ route('books.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Добавить книгу
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Фильтры и сортировка -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('books.index') }}">
                <div class="row g-3">
                    <!-- Фильтр по жанру -->
                    <div class="col-md-4">
                        <label for="genre" class="form-label">Жанр</label>
                        <select class="form-select" id="genre" name="genre">
                            <option value="">Все жанры</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                                    {{ $genre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Фильтр по страницам -->
                    <div class="col-md-4">
                        <label for="pages" class="form-label">Количество страниц</label>
                        <select class="form-select" id="pages" name="pages">
                            <option value="">Любое</option>
                            <option value="0-100" {{ request('pages') == '0-100' ? 'selected' : '' }}>До 100</option>
                            <option value="100-300" {{ request('pages') == '100-300' ? 'selected' : '' }}>100-300</option>
                            <option value="300+" {{ request('pages') == '300+' ? 'selected' : '' }}>Более 300</option>
                        </select>
                    </div>

                    <!-- Кнопки сортировки -->
                    <div class="col-md-4">
                        <label class="form-label">Сортировка</label>
                        <div class="btn-group w-100">
                            <a href="{{ request()->fullUrlWithQuery([
                                'sort' => 'title',
                                'direction' => request('sort') === 'title' && request('direction') === 'asc' ? 'desc' : 'asc',
                                'page' => null
                            ]) }}" class="btn btn-outline-secondary d-flex align-items-center">
                                По названию
                                @if(request('sort') === 'title')
                                    <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </a>
                            <a href="{{ request()->fullUrlWithQuery([
                                'sort' => 'year',
                                'direction' => request('sort') === 'year' && request('direction') === 'asc' ? 'desc' : 'asc',
                                'page' => null
                            ]) }}" class="btn btn-outline-secondary d-flex align-items-center">
                                По году
                                @if(request('sort') === 'year')
                                    <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Кнопки действия -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel"></i> Применить фильтры
                            </button>
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Сбросить
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Список книг -->
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($books as $book)
        <div class="col">
            <div class="card book-card h-100">
                <div class="book-image-container">
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" 
                             alt="Обложка {{ $book->title }}" 
                             class="book-image img-fluid p-2"
                             loading="lazy">
                    @else
                        <div class="text-center text-muted p-4">
                            <i class="bi bi-image fs-1"></i>
                            <p>Нет обложки</p>
                        </div>
                    @endif
                </div>
                <div class="card-body book-body">
                    <h5 class="card-title book-title">{{ Str::limit($book->title, 50) }}</h5>
                    <p class="card-text">
                        <small class="text-muted">{{ $book->author->name }}</small><br>
                        <span class="badge bg-secondary">{{ $book->year }}</span>
                        <span class="badge bg-info text-dark ms-1">{{ $book->genre }}</span>
                        <span class="badge bg-light text-dark ms-1">{{ $book->pages }} стр.</span>
                    </p>
                    <div class="mt-auto d-flex justify-content-between">
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Удалить эту книгу?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                Книги не найдены. <a href="{{ route('books.create') }}">Добавить книгу</a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Пагинация -->
    @if($books->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $books->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Подключение стилей и скриптов -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@vite(['resources/css/books.css', 'resources/js/books.js'])
@endsection