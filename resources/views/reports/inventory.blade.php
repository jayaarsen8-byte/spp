@extends('layouts.app')

@section('title', 'Laporan Inventory')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Laporan Inventory</h1>
    </div>

    <div class="filter-card">
        <a href="{{ route('reports.inventory.export') }}" class="btn btn-secondary">Export Excel</a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Unit</th>
                    <th>Stok</th>
                    <th>Minimum</th>
                    <th>Harga Pokok</th>
                    <th>Harga Jual</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->unit->abbreviation }}</td>
                        <td>{{ $product->stock?->quantity ?? 0 }}</td>
                        <td>{{ $product->minimum_stock }}</td>
                        <td>Rp{{ number_format($product->cost_price, 0) }}</td>
                        <td>Rp{{ number_format($product->consumer_price, 0) }}</td>
                        <td>
                            @if($product->isOutOfStock())
                                <span class="badge badge-danger">Habis</span>
                            @elseif($product->isLowStock())
                                <span class="badge badge-warning">Rendah</span>
                            @else
                                <span class="badge badge-success">Normal</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Tidak ada produk</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</div>
@endsection
