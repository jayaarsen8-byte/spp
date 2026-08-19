@extends('layouts.app')

@section('title', 'Catat Pembayaran Piutang')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Catat Pembayaran - {{ $receivable->customer->name }}</h1>
        <p class="text-muted">Piutang: Rp{{ number_format($receivable->remaining_amount, 0) }}</p>
    </div>

    <form action="{{ route('receivable-payments.store', $receivable) }}" method="POST" class="form-container">
        @csrf

        <div class="form-section">
            <h3>Informasi Pembayaran</h3>
            <div class="form-group">
                <label for="amount">Jumlah Pembayaran *</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" id="amount" name="amount" step="0.01" class="form-control @error('amount') is-invalid @enderror" value="{{ $receivable->remaining_amount }}" required>
                </div>
                <small class="form-text text-muted">Maksimal: Rp{{ number_format($receivable->remaining_amount, 0) }}</small>
                @error('amount')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="method">Metode Pembayaran *</label>
                <select id="method" name="method" class="form-control @error('method') is-invalid @enderror" required>
                    <option value="cash">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="qris">QRIS</option>
                    <option value="debit">Debit</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
                @error('method')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="note">Catatan</label>
                <textarea id="note" name="note" class="form-control" rows="3" placeholder="Contoh: Pembayaran sebagian, referensi transfer, dll."></textarea>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('receivables.show', $receivable) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Catat Pembayaran</button>
        </div>
    </form>
</div>
@endsection
