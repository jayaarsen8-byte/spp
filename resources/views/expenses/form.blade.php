@extends('layouts.app')

@section('title', isset($expense) ? 'Edit Pengeluaran' : 'Tambah Pengeluaran')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>{{ isset($expense) ? 'Edit Pengeluaran' : 'Tambah Pengeluaran' }}</h1>
    </div>

    <form action="{{ isset($expense) ? route('expenses.update', $expense) : route('expenses.store') }}" method="POST" class="form-container">
        @csrf
        @if(isset($expense))
            @method('PUT')
        @endif

        <div class="form-section">
            <h3>Detail Pengeluaran</h3>
            <div class="form-group">
                <label for="category_id">Kategori *</label>
                <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (isset($expense) && $expense->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Deskripsi *</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ $expense->description ?? old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="amount">Jumlah *</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="amount" name="amount" step="0.01" class="form-control @error('amount') is-invalid @enderror" value="{{ $expense->amount ?? old('amount') }}" required>
                    </div>
                    @error('amount')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="expense_date">Tanggal *</label>
                    <input type="date" id="expense_date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ $expense->expense_date ? $expense->expense_date->format('Y-m-d') : old('expense_date', date('Y-m-d')) }}" required>
                    @error('expense_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">{{ isset($expense) ? 'Simpan' : 'Tambah Pengeluaran' }}</button>
        </div>
    </form>
</div>
@endsection
