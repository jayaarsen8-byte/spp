@extends('layouts.app')

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>{{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}</h1>
    </div>

    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data" class="form-container">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="form-section">
            <h3>Informasi Umum</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="sku">SKU *</label>
                    <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ $product->sku ?? old('sku') }}" required>
                    @error('sku')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="barcode">Barcode</label>
                    <input type="text" id="barcode" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ $product->barcode ?? old('barcode') }}">
                    @error('barcode')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="name">Nama Produk *</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $product->name ?? old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" rows="3">{{ $product->description ?? old('description') }}</textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Kategori & Unit</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="category_id">Kategori *</label>
                    <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (isset($product) && $product->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="unit_id">Unit *</label>
                    <select id="unit_id" name="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ (isset($product) && $product->unit_id == $unit->id) || old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} ({{ $unit->abbreviation }})
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Harga</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="cost_price">Harga Pokok (HPP) *</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="cost_price" name="cost_price" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" value="{{ $product->cost_price ?? old('cost_price') }}" required>
                    </div>
                    @error('cost_price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="consumer_price">Harga Konsumen *</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="consumer_price" name="consumer_price" step="0.01" class="form-control @error('consumer_price') is-invalid @enderror" value="{{ $product->consumer_price ?? old('consumer_price') }}" required>
                    </div>
                    @error('consumer_price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="applicator_price">Harga Aplikator *</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="applicator_price" name="applicator_price" step="0.01" class="form-control @error('applicator_price') is-invalid @enderror" value="{{ $product->applicator_price ?? old('applicator_price') }}" required>
                    </div>
                    @error('applicator_price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="buyer_price">Harga Pembeli *</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="buyer_price" name="buyer_price" step="0.01" class="form-control @error('buyer_price') is-invalid @enderror" value="{{ $product->buyer_price ?? old('buyer_price') }}" required>
                    </div>
                    @error('buyer_price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Kalkulasi & Stok</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="calculation_type">Tipe Kalkulasi *</label>
                    <select id="calculation_type" name="calculation_type" class="form-control @error('calculation_type') is-invalid @enderror" required>
                        <option value="quantity" {{ (isset($product) && $product->calculation_type == 'quantity') || old('calculation_type') == 'quantity' ? 'selected' : '' }}>Quantity</option>
                        <option value="meter" {{ (isset($product) && $product->calculation_type == 'meter') || old('calculation_type') == 'meter' ? 'selected' : '' }}>Meter</option>
                        <option value="sheet_meter" {{ (isset($product) && $product->calculation_type == 'sheet_meter') || old('calculation_type') == 'sheet_meter' ? 'selected' : '' }}>Lembar × Meter</option>
                    </select>
                    @error('calculation_type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="minimum_stock">Minimum Stok *</label>
                    <input type="number" id="minimum_stock" name="minimum_stock" step="0.01" class="form-control @error('minimum_stock') is-invalid @enderror" value="{{ $product->minimum_stock ?? old('minimum_stock') }}" required>
                    @error('minimum_stock')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Simpan' : 'Tambah Produk' }}</button>
        </div>
    </form>
</div>
@endsection
