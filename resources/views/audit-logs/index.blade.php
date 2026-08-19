@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Audit Log</h1>
        <p class="text-muted">Riwayat semua aktivitas sistem.</p>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Model</th>
                    <th>Deskripsi</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                        <td>{{ $log->user->name }}</td>
                        <td><span class="badge badge-info">{{ $log->action }}</span></td>
                        <td>{{ $log->model }}</td>
                        <td>{{ $log->description }}</td>
                        <td><small>{{ $log->ip_address }}</small></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada aktivitas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
