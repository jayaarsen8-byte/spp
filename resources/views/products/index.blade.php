@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="page-container">
    <div class="page-header-actions">
        <div class="page-header">
            <h1>Produk</h1>
            <p class="text-muted">Kelola katalog dan harga produk Anda.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Unit</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td><small class="text-muted">{{ $product->sku }}</small></td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->unit->abbreviation }}</td>
                        <td>Rp{{ number_format($product->consumer_price, 0) }}</td>
                        <td>
                            @if($product->stock)
                                <span class="badge badge-{{ $product->isOutOfStock() ? 'danger' : ($product->isLowStock() ? 'warning' : 'success') }}">
                                    {{ $product->stock->quantity }}
                                </span>
                            @else
                                <span class="badge badge-danger">0</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="icon-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p>Belum ada produk</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
</div>
@endsection
