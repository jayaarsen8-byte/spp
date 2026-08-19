@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="page-header">
        <h1>Selamat Pagi, {{ auth()->user()->name }}</h1>
        <p class="text-muted">Berikut adalah ringkasan bisnis Anda hari ini.</p>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card stat-card-primary">
            <div class="stat-icon"><i class="icon-trending-up"></i></div>
            <div class="stat-content">
                <p class="stat-label">Revenue Hari Ini</p>
                <h3 class="stat-value">Rp{{ number_format($revenue_today, 0) }}</h3>
            </div>
        </div>
        <div class="stat-card stat-card-success">
            <div class="stat-icon"><i class="icon-shopping-cart"></i></div>
            <div class="stat-content">
                <p class="stat-label">Transaksi Hari Ini</p>
                <h3 class="stat-value">{{ $sales_today }}</h3>
            </div>
        </div>
        <div class="stat-card stat-card-warning">
            <div class="stat-icon"><i class="icon-alert-circle"></i></div>
            <div class="stat-content">
                <p class="stat-label">Stok Rendah</p>
                <h3 class="stat-value">{{ $low_stock_count }}</h3>
            </div>
        </div>
        <div class="stat-card stat-card-info">
            <div class="stat-icon"><i class="icon-credit-card"></i></div>
            <div class="stat-content">
                <p class="stat-label">Piutang</p>
                <h3 class="stat-value">Rp{{ number_format($receivables, 0) }}</h3>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Revenue Bulanan</h2>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Produk Terlaris</h2>
        <div class="products-grid">
            @foreach($top_products as $product)
                <div class="product-card">
                    <h4>{{ $product->name }}</h4>
                    <p class="text-muted">{{ $product->quantity_sold }} terjual</p>
                    <h5>Profit: Rp{{ number_format($product->total_profit, 0) }}</h5>
                </div>
            @endforeach
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Business Insights</h2>
        <div class="insights-list">
            @foreach($insights as $insight)
                <div class="insight-item">
                    <i class="icon-info"></i>
                    <p>{{ $insight }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chart_data['labels']) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($chart_data['data']) !!},
            borderColor: '#000',
            backgroundColor: 'rgba(0, 0, 0, 0.05)',
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
