@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-container">
    <div class="page-header">
        <h1>Selamat Pagi, {{ auth()->user()->name }}</h1>
        <p class="text-muted">Kelola operasional toko Anda.</p>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card stat-card-primary">
            <div class="stat-icon"><i class="icon-shopping-cart"></i></div>
            <div class="stat-content">
                <p class="stat-label">Transaksi Hari Ini</p>
                <h3 class="stat-value">{{ $sales_today }}</h3>
            </div>
        </div>
        <div class="stat-card stat-card-success">
            <div class="stat-icon"><i class="icon-trending-up"></i></div>
            <div class="stat-content">
                <p class="stat-label">Revenue Hari Ini</p>
                <h3 class="stat-value">Rp{{ number_format($revenue_today, 0) }}</h3>
            </div>
        </div>
        <div class="stat-card stat-card-info">
            <div class="stat-icon"><i class="icon-package"></i></div>
            <div class="stat-content">
                <p class="stat-label">Total Produk</p>
                <h3 class="stat-value">{{ $products_count }}</h3>
            </div>
        </div>
        <div class="stat-card stat-card-warning">
            <div class="stat-icon"><i class="icon-users"></i></div>
            <div class="stat-content">
                <p class="stat-label">Total Pelanggan</p>
                <h3 class="stat-value">{{ $customers_count }}</h3>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Akses Cepat</h2>
        <div class="quick-actions">
            <a href="{{ route('pos.index') }}" class="action-btn">
                <i class="icon-shopping-cart"></i>
                <span>Buat Penjualan</span>
            </a>
            <a href="{{ route('products.index') }}" class="action-btn">
                <i class="icon-package"></i>
                <span>Kelola Produk</span>
            </a>
            <a href="{{ route('customers.index') }}" class="action-btn">
                <i class="icon-users"></i>
                <span>Kelola Pelanggan</span>
            </a>
            <a href="{{ route('sales.index') }}" class="action-btn">
                <i class="icon-file-text"></i>
                <span>Lihat Penjualan</span>
            </a>
        </div>
    </div>
</div>
@endsection
