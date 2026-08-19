@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Laporan Penjualan</h1>
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
                    <a href="{{ route('reports.sales.export', ['start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-secondary">Export Excel</a>
                </div>
            </div>
        </form>
    </div>

    @if($summary)
    <div class="summary-cards">
        <div class="summary-card">
            <h4>Jumlah Transaksi</h4>
            <h3>{{ $summary->transaction_count ?? 0 }}</h3>
        </div>
        <div class="summary-card">
            <h4>Total Revenue</h4>
            <h3>Rp{{ number_format($summary->total_revenue ?? 0, 0) }}</h3>
        </div>
        <div class="summary-card">
            <h4>Total Diskon</h4>
            <h3>Rp{{ number_format($summary->total_discount ?? 0, 0) }}</h3>
        </div>
    </div>
    @endif

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Subtotal</th>
                    <th>Diskon</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->sold_at->format('d-m-Y') }}</td>
                        <td>{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                        <td>Rp{{ number_format($sale->subtotal_normal, 0) }}</td>
                        <td>Rp{{ number_format($sale->total_discount, 0) }}</td>
                        <td><strong>Rp{{ number_format($sale->grand_total, 0) }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $sales->links() }}
</div>
@endsection
