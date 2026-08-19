@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('content')
<div class="page-container">
    <div class="page-header-actions">
        <div class="page-header">
            <h1>Pengeluaran</h1>
            <p class="text-muted">Catat semua pengeluaran operasional toko.</p>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">+ Tambah Pengeluaran</a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Pengeluaran</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Dicatat oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td><strong>{{ $expense->number }}</strong></td>
                        <td>{{ $expense->expense_date->format('d-m-Y') }}</td>
                        <td>{{ $expense->category->name }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>Rp{{ number_format($expense->amount, 0) }}</td>
                        <td>{{ $expense->user->name }}</td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada pengeluaran</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $expenses->links() }}
</div>
@endsection
