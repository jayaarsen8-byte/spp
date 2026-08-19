@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="page-container">
    <div class="page-header-actions">
        <div class="page-header">
            <h1>{{ $sale->invoice_number }}</h1>
            <p class="text-muted">{{ $sale->sold_at->format('d-m-Y H:i') }}</p>
        </div>
        <div>
            <a href="{{ route('sales.print', $sale) }}" class="btn btn-secondary" target="_blank">Cetak</a>
            @if($sale->status == 'completed')
                <form action="{{ route('sales.cancel', $sale) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin?');">
                    @csrf
                    <button type="submit" class="btn btn-danger">Batalkan Penjualan</button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3>Detail Penjualan</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Meter</th>
                                <th>Harga Normal</th>
                                <th>Harga Jual</th>
                                <th>Diskon</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->total_meter ?? '-' }}</td>
                                    <td>Rp{{ number_format($item->normal_unit_price, 0) }}</td>
                                    <td>Rp{{ number_format($item->selling_unit_price, 0) }}</td>
                                    <td>Rp{{ number_format($item->total_discount, 0) }}</td>
                                    <td><strong>Rp{{ number_format($item->subtotal, 0) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h3>Ringkasan</h3>
                </div>
                <div class="card-body">
                    <div class="summary-item">
                        <span>Pelanggan:</span>
                        <strong>{{ $sale->customer?->name ?? 'Walk-in' }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>Kasir:</span>
                        <strong>{{ $sale->user->name }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>Tipe Harga:</span>
                        <strong>{{ ucfirst($sale->price_type) }}</strong>
                    </div>
                    <hr>
                    <div class="summary-item">
                        <span>Subtotal:</span>
                        <strong>Rp{{ number_format($sale->subtotal_normal, 0) }}</strong>
                    </div>
                    <div class="summary-item text-danger">
                        <span>Diskon:</span>
                        <strong>-Rp{{ number_format($sale->total_discount, 0) }}</strong>
                    </div>
                    <div class="summary-item summary-total">
                        <span>Grand Total:</span>
                        <strong>Rp{{ number_format($sale->grand_total, 0) }}</strong>
                    </div>
                    <hr>
                    <div class="summary-item">
                        <span>Dibayar:</span>
                        <strong>Rp{{ number_format($sale->payment_amount, 0) }}</strong>
                    </div>
                    @if($sale->change_amount > 0)
                        <div class="summary-item">
                            <span>Kembali:</span>
                            <strong>Rp{{ number_format($sale->change_amount, 0) }}</strong>
                        </div>
                    @endif
                    @if($sale->receivable_amount > 0)
                        <div class="summary-item text-warning">
                            <span>Piutang:</span>
                            <strong>Rp{{ number_format($sale->receivable_amount, 0) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
