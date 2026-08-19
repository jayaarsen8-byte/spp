@extends('layouts.app')

@section('title', 'Piutang')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Daftar Piutang</h1>
        <p class="text-muted">Kelola piutang pelanggan Anda.</p>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Sudah Dibayar</th>
                    <th>Sisa Piutang</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receivables as $receivable)
                    <tr>
                        <td><strong>{{ $receivable->customer->name }}</strong></td>
                        <td>{{ $receivable->sale->invoice_number }}</td>
                        <td>Rp{{ number_format($receivable->total_amount, 0) }}</td>
                        <td>Rp{{ number_format($receivable->paid_amount, 0) }}</td>
                        <td><strong>Rp{{ number_format($receivable->remaining_amount, 0) }}</strong></td>
                        <td>{{ $receivable->due_date ? $receivable->due_date->format('d-m-Y') : '-' }}</td>
                        <td>
                            @if($receivable->status == 'paid')
                                <span class="badge badge-success">Lunas</span>
                            @elseif($receivable->status == 'partial')
                                <span class="badge badge-warning">Sebagian</span>
                            @elseif($receivable->status == 'overdue')
                                <span class="badge badge-danger">Jatuh Tempo</span>
                            @else
                                <span class="badge badge-secondary">Belum Bayar</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('receivables.show', $receivable) }}" class="btn btn-sm btn-info">Lihat</a>
                                @if($receivable->status != 'paid')
                                    <a href="{{ route('receivable-payments.form', $receivable) }}" class="btn btn-sm btn-primary">Bayar</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada piutang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $receivables->links() }}
</div>
@endsection
