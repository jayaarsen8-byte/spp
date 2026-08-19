@extends('layouts.app')

@section('title', isset($unit) ? 'Edit Unit' : 'Tambah Unit')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>{{ isset($unit) ? 'Edit Unit' : 'Tambah Unit' }}</h1>
    </div>

    <form action="{{ isset($unit) ? route('units.update', $unit) : route('units.store') }}" method="POST" class="form-container">
        @csrf
        @if(isset($unit))
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nama Unit *</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $unit->name ?? old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="abbreviation">Singkatan *</label>
                <input type="text" id="abbreviation" name="abbreviation" class="form-control @error('abbreviation') is-invalid @enderror" value="{{ $unit->abbreviation ?? old('abbreviation') }}" required>
                @error('abbreviation')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" class="form-control" rows="3">{{ $unit->description ?? old('description') }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('units.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">{{ isset($unit) ? 'Simpan' : 'Tambah' }}</button>
        </div>
    </form>
</div>
@endsection
