@extends('layouts.app')

@section('title', isset($customer) ? 'Edit Pelanggan' : 'Tambah Pelanggan')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>{{ isset($customer) ? 'Edit Pelanggan' : 'Tambah Pelanggan' }}</h1>
    </div>

    <form action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}" method="POST" class="form-container">
        @csrf
        @if(isset($customer))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Nama Pelanggan *</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $customer->name ?? old('name') }}" required>
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="phone">Telepon</label>
                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ $customer->phone ?? old('phone') }}">
                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="customer_type">Tipe Pelanggan *</label>
                <select id="customer_type" name="customer_type" class="form-control @error('customer_type') is-invalid @enderror" required>
                    <option value="consumer" {{ (isset($customer) && $customer->customer_type == 'consumer') || old('customer_type') == 'consumer' ? 'selected' : '' }}>Konsumen</option>
                    <option value="applicator" {{ (isset($customer) && $customer->customer_type == 'applicator') || old('customer_type') == 'applicator' ? 'selected' : '' }}>Aplikator</option>
                    <option value="buyer" {{ (isset($customer) && $customer->customer_type == 'buyer') || old('customer_type') == 'buyer' ? 'selected' : '' }}>Pembeli</option>
                </select>
                @error('customer_type')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="address">Alamat</label>
            <textarea id="address" name="address" class="form-control" rows="3">{{ $customer->address ?? old('address') }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">{{ isset($customer) ? 'Simpan' : 'Tambah Pelanggan' }}</button>
        </div>
    </form>
</div>
@endsection
