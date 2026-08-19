@extends('layouts.app')

@section('title', 'Laporan Profit')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Laporan Profit</h1>
    </div>

    <div class="filter-card">
        <form method="GET" class="filter-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="start_date">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date }}">
                </div>
                <div class="form-group">
                    <label for="end_date">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date }}">
                </div>
                <div class="form-group align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <h4>Total Revenue</h4>
            <h3>Rp{{ number_format($summary['total_revenue'], 0) }}</h3>
        </div>
        <div class="summary-card">
            <h4>Total Cost</h4>
            <h3>Rp{{ number_format($summary['total_cost'], 0) }}</h3>
        </div>
        <div class="summary-card">
            <h4>Gross Profit</h4>
            <h3>Rp{{ number_format($summary['gross_profit'], 0) }}</h3>
        </div>
        <div class="summary-card">
            <h4>Net Profit</h4>
            <h3>Rp{{ number_format($summary['net_profit'], 0) }}</h3>
            <small>Profit Margin: {{ $summary['profit_margin'] }}%</small>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Profit Berdasarkan Produk</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Total Revenue</th>
                                <th>Total Profit</th>
                                <th>Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($by_product as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Unknown' }}</td>
                                    <td>Rp{{ number_format($item->total_revenue, 0) }}</td>
                                    <td>Rp{{ number_format($item->total_profit, 0) }}</td>
                                    <td>{{ $item->total_revenue > 0 ? round(($item->total_profit / $item->total_revenue) * 100, 2) : 0 }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Profit Berdasarkan Kategori</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Total Revenue</th>
                                <th>Total Profit</th>
                                <th>Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($by_category as $item)
                                <tr>
                                    <td>{{ $item['category'] }}</td>
                                    <td>Rp{{ number_format($item['total_revenue'], 0) }}</td>
                                    <td>Rp{{ number_format($item['total_profit'], 0) }}</td>
                                    <td>{{ $item['total_revenue'] > 0 ? round(($item['total_profit'] / $item['total_revenue']) * 100, 2) : 0 }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
