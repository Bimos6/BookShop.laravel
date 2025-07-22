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

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'title',
                                    'direction' => request('sort') === 'title' && request('direction') === 'asc' ? 'desc' : 'asc'
                                ]) }}" class="text-decoration-none text-dark">
                                    Название
                                    @if(request('sort') === 'title')
                                        @if(request('direction') === 'asc')
                                            <i class="bi bi-arrow-up"></i>
                                        @else
                                            <i class="bi bi-arrow-down"></i>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'author',
                                    'direction' => request('sort') === 'author' && request('direction') === 'asc' ? 'desc' : 'asc'
                                ]) }}" class="text-decoration-none text-dark">
                                    Автор
                                    @if(request('sort') === 'author')
                                        @if(request('direction') === 'asc')
                                            <i class="bi bi-arrow-up"></i>
                                        @else
                                            <i class="bi bi-arrow-down"></i>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th>Год</th>
                            <th>Жанр</th>
                            <th>Обложка</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author->name }}</td>
                                <td>{{ $book->year }}</td>
                                <td>{{ $book->genre }}</td>
                                <td>
                                    @if($book->cover)
                                        <img src="{{ asset('storage/' . $book->cover) }}" 
                                             alt="Обложка {{ $book->title }}" 
                                             class="img-thumbnail" 
                                             width="50" 
                                             style="object-fit: cover; height: 70px;">
                                    @else
                                        <span class="badge bg-secondary">Нет обложки</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('books.show', $book->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Просмотреть">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('books.edit', $book->id) }}" 
                                       class="btn btn-sm btn-outline-warning"
                                       title="Редактировать">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                title="Удалить"
                                                onclick="return confirm('Вы уверены что хотите удалить эту книгу?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="alert alert-info">
                                        Книги не найдены. <a href="{{ route('books.create') }}">Добавить книгу</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $books->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .table th a:hover { color: var(--bs-primary); }
    .img-thumbnail { background-color: #f8f9fa; }
</style>
@endsection