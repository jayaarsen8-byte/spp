@extends('layouts.app')

@section('title', 'Laporan Pengeluaran')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Laporan Pengeluaran</h1>
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
                    <a href="{{ route('reports.expense.export', ['start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-secondary">Export Excel</a>
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
            <h4>Total Pengeluaran</h4>
            <h3>Rp{{ number_format($summary->total_amount ?? 0, 0) }}</h3>
        </div>
    </div>
    @endif

    <div class="row mt-4">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3>Detail Pengeluaran</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Pengeluaran</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->number }}</td>
                                    <td>{{ $expense->expense_date->format('d-m-Y') }}</td>
                                    <td>{{ $expense->category->name }}</td>
                                    <td>{{ $expense->description }}</td>
                                    <td>Rp{{ number_format($expense->amount, 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $expenses->links() }}
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h3>Pengeluaran Berdasarkan Kategori</h3>
                </div>
                <div class="card-body">
                    @forelse($by_category as $item)
                        <div class="category-item">
                            <span>{{ $item->name }}</span>
                            <strong>Rp{{ number_format($item->total_amount, 0) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
