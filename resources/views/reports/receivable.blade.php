@extends('layouts.app')

@section('title', 'Laporan Piutang')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Laporan Piutang</h1>
    </div>

    <div class="filter-card">
        <a href="{{ route('reports.receivable.export') }}" class="btn btn-secondary">Export Excel</a>
    </div>

    @if($summary)
    <div class="summary-cards">
        <div class="summary-card">
            <h4>Total Piutang</h4>
            <h3>{{ $summary->total_receivables ?? 0 }}</h3>
        </div>
        <div class="summary-card">
            <h4>Total Amount</h4>
            <h3>Rp{{ number_format($summary->total_amount ?? 0, 0) }}</h3>
        </div>
        <div class="summary-card">
            <h4>Sisa Piutang</h4>
            <h3>Rp{{ number_format($summary->total_remaining ?? 0, 0) }}</h3>
        </div>
    </div>
    @endif

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Sudah Bayar</th>
                    <th>Sisa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receivables as $receivable)
                    <tr>
                        <td>{{ $receivable->customer->name }}</td>
                        <td>{{ $receivable->sale->invoice_number }}</td>
                        <td>Rp{{ number_format($receivable->total_amount, 0) }}</td>
                        <td>Rp{{ number_format($receivable->paid_amount, 0) }}</td>
                        <td>Rp{{ number_format($receivable->remaining_amount, 0) }}</td>
                        <td>
                            @if($receivable->status == 'paid')
                                <span class="badge badge-success">Lunas</span>
                            @elseif($receivable->status == 'partial')
                                <span class="badge badge-warning">Sebagian</span>
                            @else
                                <span class="badge badge-secondary">Belum</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $receivables->links() }}
</div>
@endsection
