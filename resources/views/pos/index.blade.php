@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<div class="pos-container">
    <div class="pos-products">
        <div class="pos-header">
            <input type="text" id="product-search" class="form-control" placeholder="Cari produk atau barcode..." autofocus>
            <select id="category-filter" class="form-control">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="pos-grid" id="product-grid">
            <!-- Loaded via AJAX -->
        </div>
    </div>

    <div class="pos-checkout">
        <h2>Keranjang</h2>
        <div class="cart-items" id="cart-items">
            <!-- Items added here -->
        </div>
        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal Normal:</span>
                <strong id="subtotal-normal">Rp0</strong>
            </div>
            <div class="summary-row">
                <span>Total Diskon:</span>
                <strong id="total-discount" class="text-danger">Rp0</strong>
            </div>
            <div class="summary-row summary-grand-total">
                <span>Grand Total:</span>
                <strong id="grand-total">Rp0</strong>
            </div>
        </div>

        <div class="pos-form">
            <div class="form-group">
                <label for="price-type">Tipe Harga</label>
                <select id="price-type" class="form-control">
                    <option value="consumer">Konsumen</option>
                    <option value="applicator">Aplikator</option>
                    <option value="buyer">Pembeli</option>
                </select>
            </div>

            <div class="form-group">
                <label for="customer-id">Pelanggan (Opsional)</label>
                <select id="customer-id" class="form-control">
                    <option value="">Walk-in</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="payment-method">Metode Pembayaran</label>
                <select id="payment-method" class="form-control">
                    <option value="cash">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="qris">QRIS</option>
                    <option value="debit">Debit</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>

            <div class="form-group">
                <label for="payment-amount">Jumlah Pembayaran</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" id="payment-amount" class="form-control" step="0.01" value="0">
                </div>
            </div>

            <div class="form-group">
                <label>Kembali:</label>
                <div class="text-large" id="change-amount">Rp0</div>
            </div>
        </div>

        <div class="pos-actions">
            <button id="clear-cart" class="btn btn-secondary btn-block">Batal</button>
            <button id="checkout-btn" class="btn btn-primary btn-block">Selesaikan (F10)</button>
        </div>
    </div>
</div>

<!-- Product Detail Modal -->
<div class="modal" id="productModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-product-name"></h3>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="modal-product-details">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" id="add-to-cart-btn">Tambah ke Keranjang</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/pos.js') }}"></script>
@endsection
