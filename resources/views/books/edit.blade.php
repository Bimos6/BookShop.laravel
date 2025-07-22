@extends('layouts.app')

@section('title', 'Редактировать книгу')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Редактировать: {{ $book->title }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="author_name" class="form-label">Автор *</label>
                            <input type="text" class="form-control @error('author_name') is-invalid @enderror" 
                                   id="author_name" name="author_name" 
                                   value="{{ old('author_name', $book->author->name) }}" required>
                            @error('author_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Название *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" 
                                   value="{{ old('title', $book->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="year" class="form-label">Год издания *</label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                       id="year" name="year" 
                                       value="{{ old('year', $book->year) }}" required>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="genre" class="form-label">Жанр *</label>
                                <input type="text" class="form-control @error('genre') is-invalid @enderror" 
                                       id="genre" name="genre" 
                                       value="{{ old('genre', $book->genre) }}" required>
                                @error('genre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="pages" class="form-label">Страниц *</label>
                                <input type="number" class="form-control @error('pages') is-invalid @enderror" 
                                       id="pages" name="pages" min="1" 
                                       value="{{ old('pages', $book->pages) }}" required>
                                @error('pages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Текущая обложка</label>
                            @if($book->cover)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $book->cover) }}" 
                                         alt="Текущая обложка" 
                                         class="img-thumbnail" 
                                         width="150">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="remove_cover" name="remove_cover"
                                           {{ old('remove_cover') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remove_cover">
                                        Удалить текущую обложку
                                    </label>
                                </div>
                            @else
                                <p class="text-muted">Обложка не загружена</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="cover" class="form-label">Новая обложка</label>
                            <input type="file" class="form-control @error('cover') is-invalid @enderror" 
                                   id="cover" name="cover" accept="image/*">
                            @error('cover')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Формат: JPEG, PNG. Макс. размер: 2MB</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" 
                                      rows="5">{{ old('description', $book->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('books.index') }}" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Отмена
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .img-thumbnail {
        background-color: #f8f9fa;
        object-fit: cover;
    }
    textarea {
        min-height: 150px;
    }
</style>
@endsection