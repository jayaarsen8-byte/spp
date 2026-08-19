@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
<div class="page-container">
    <div class="page-header-actions">
        <div class="page-header">
            <h1>Pelanggan</h1>
            <p class="text-muted">Kelola data pelanggan Anda.</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Tambah Pelanggan</a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>{{ $customer->address ?? '-' }}</td>
                        <td>
                            <span class="badge badge-info">
                                @if($customer->customer_type == 'consumer')
                                    Konsumen
                                @elseif($customer->customer_type == 'applicator')
                                    Aplikator
                                @else
                                    Pembeli
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($customer->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pelanggan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $customers->links() }}
</div>
@endsection
