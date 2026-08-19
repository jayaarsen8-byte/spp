@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Daftar Penjualan</h1>
        <p class="text-muted">Kelola transaksi penjualan Anda.</p>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Invoice</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Tipe Harga</th>
                    <th>Grand Total</th>
                    <th>Dibayar</th>
                    <th>Piutang</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td><strong>{{ $sale->invoice_number }}</strong></td>
                        <td>{{ $sale->sold_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                        <td>
                            @if($sale->price_type == 'consumer')
                                Konsumen
                            @elseif($sale->price_type == 'applicator')
                                Aplikator
                            @else
                                Pembeli
                            @endif
                        </td>
                        <td><strong>Rp{{ number_format($sale->grand_total, 0) }}</strong></td>
                        <td>Rp{{ number_format($sale->payment_amount, 0) }}</td>
                        <td>Rp{{ number_format($sale->receivable_amount, 0) }}</td>
                        <td>
                            @if($sale->status == 'completed')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info">Lihat</a>
                                <a href="{{ route('sales.print', $sale) }}" class="btn btn-sm btn-secondary" target="_blank">Cetak</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada penjualan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $sales->links() }}
</div>
@endsection
