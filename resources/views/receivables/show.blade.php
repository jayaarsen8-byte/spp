@extends('layouts.app')

@section('title', 'Detail Piutang')

@section('content')
<div class="page-container">
    <div class="page-header-actions">
        <div class="page-header">
            <h1>Piutang dari {{ $receivable->customer->name }}</h1>
            <p class="text-muted">{{ $receivable->sale->invoice_number }}</p>
        </div>
        @if($receivable->status != 'paid')
            <a href="{{ route('receivable-payments.form', $receivable) }}" class="btn btn-primary">Catat Pembayaran</a>
        @endif
    </div>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3>Detail Transaksi</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receivable->sale->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp{{ number_format($item->selling_unit_price, 0) }}</td>
                                    <td>Rp{{ number_format($item->subtotal, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3>Riwayat Pembayaran</h3>
                </div>
                <div class="card-body">
                    @if($receivable->payments->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Metode</th>
                                    <th>Jumlah</th>
                                    <th>Dicatat oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($receivable->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->paid_at->format('d-m-Y H:i') }}</td>
                                        <td>{{ ucfirst($payment->method) }}</td>
                                        <td>Rp{{ number_format($payment->amount, 0) }}</td>
                                        <td>{{ $payment->user->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Belum ada pembayaran</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h3>Ringkasan Piutang</h3>
                </div>
                <div class="card-body">
                    <div class="summary-item">
                        <span>Total Piutang:</span>
                        <strong>Rp{{ number_format($receivable->total_amount, 0) }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>Sudah Dibayar:</span>
                        <strong>Rp{{ number_format($receivable->paid_amount, 0) }}</strong>
                    </div>
                    <div class="summary-item summary-total">
                        <span>Sisa Piutang:</span>
                        <strong>Rp{{ number_format($receivable->remaining_amount, 0) }}</strong>
                    </div>
                    <hr>
                    <div class="summary-item">
                        <span>Status:</span>
                        @if($receivable->status == 'paid')
                            <span class="badge badge-success">Lunas</span>
                        @elseif($receivable->status == 'partial')
                            <span class="badge badge-warning">Sebagian</span>
                        @elseif($receivable->status == 'overdue')
                            <span class="badge badge-danger">Jatuh Tempo</span>
                        @else
                            <span class="badge badge-secondary">Belum Bayar</span>
                        @endif
                    </div>
                    @if($receivable->due_date)
                        <div class="summary-item">
                            <span>Jatuh Tempo:</span>
                            <strong>{{ $receivable->due_date->format('d-m-Y') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
