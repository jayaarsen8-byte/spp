@extends('layouts.app')

@section('title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}</h1>
    </div>

    <form action="{{ isset($category) ? route('expense-categories.update', $category) : route('expense-categories.store') }}" method="POST" class="form-container">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Nama Kategori *</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $category->name ?? old('name') }}" required>
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="3">{{ $category->description ?? old('description') }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('expense-categories.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Simpan' : 'Tambah' }}</button>
        </div>
    </form>
</div>
@endsection
