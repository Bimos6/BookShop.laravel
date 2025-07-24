@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
            @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" 
                     class="img-fluid rounded shadow" 
                     alt="{{ $book->title }}"
                     style="max-height: 500px; width: 100%; object-fit: contain;">
            @else
                <div class="bg-light p-5 text-center rounded">
                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-2 text-muted">Обложка отсутствует</p>
                </div>
            @endif
        </div>
        
        <div class="col-md-8">
            <h1 class="mb-3">{{ $book->title }}</h1>
            <h4 class="text-muted mb-4">{{ $book->author->name }}</h4>
            
            <div class="d-flex gap-2 mb-4">
                <span class="badge bg-primary">{{ $book->genre }}</span>
                <span class="badge bg-secondary">{{ $book->year }} год</span>
                <span class="badge bg-info">{{ $book->pages }} стр.</span>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Описание</h5>
                </div>
                <div class="card-body">
                    @if($book->description)
                        <p class="card-text">{{ $book->description }}</p>
                    @else
                        <p class="text-muted mb-0">Описание отсутствует</p>
                    @endif
                </div>
            </div>
            
            <div class="d-flex flex-wrap gap-2">
                @if(session('admin_mode'))
                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary px-4">
                        <i class="bi bi-pencil"></i> Редактировать
                    </a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4"
                                onclick="return confirm('Удалить эту книгу?')">
                            <i class="bi bi-trash"></i> Удалить
                        </button>
                    </form>
                @endif
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left"></i> Назад
                </a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .card-header { padding: 0.75rem 1.25rem; }
</style>
@endsection