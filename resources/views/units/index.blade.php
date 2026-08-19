@extends('layouts.app')

@section('title', 'Unit')

@section('content')
<div class="page-container">
    <div class="page-header-actions">
        <div class="page-header">
            <h1>Unit</h1>
        </div>
        <a href="{{ route('units.create') }}" class="btn btn-primary">+ Tambah</a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Singkatan</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $unit)
                    <tr>
                        <td><strong>{{ $unit->name }}</strong></td>
                        <td><code>{{ $unit->abbreviation }}</code></td>
                        <td>{{ $unit->description }}</td>
                        <td>
                            @if($unit->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada unit</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $units->links() }}
</div>
@endsection
