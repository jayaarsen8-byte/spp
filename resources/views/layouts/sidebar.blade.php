<aside class="app-sidebar">
    <div class="sidebar-header">
        <h1 class="app-logo">SPP</h1>
        <button class="sidebar-toggle" id="sidebar-toggle">
            <i class="icon-menu"></i>
        </button>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="icon-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('pos.index') }}" class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
            <i class="icon-shopping-cart"></i>
            <span>POS</span>
        </a>
        <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="icon-package"></i>
            <span>Produk</span>
        </a>
        <a href="{{ route('customers.index') }}" class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="icon-users"></i>
            <span>Pelanggan</span>
        </a>
        <a href="{{ route('sales.index') }}" class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i class="icon-file-text"></i>
            <span>Penjualan</span>
        </a>
        <a href="{{ route('receivables.index') }}" class="nav-item {{ request()->routeIs('receivables.*') ? 'active' : '' }}">
            <i class="icon-credit-card"></i>
            <span>Piutang</span>
        </a>
        <a href="{{ route('expenses.index') }}" class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="icon-trending-down"></i>
            <span>Pengeluaran</span>
        </a>
        <div class="nav-divider"></div>
        <a href="{{ route('reports.sales') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="icon-bar-chart-2"></i>
            <span>Laporan</span>
        </a>
        @auth
            @if(auth()->user()->isOwner())
                <div class="nav-divider"></div>
                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="icon-layers"></i>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('units.index') }}" class="nav-item {{ request()->routeIs('units.*') ? 'active' : '' }}">
                    <i class="icon-box"></i>
                    <span>Unit</span>
                </a>
                <a href="{{ route('expense-categories.index') }}" class="nav-item {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                    <i class="icon-folder"></i>
                    <span>Kategori Pengeluaran</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="icon-lock"></i>
                    <span>Admin</span>
                </a>
                <a href="{{ route('password-resets.index') }}" class="nav-item {{ request()->routeIs('password-resets.*') ? 'active' : '' }}">
                    <i class="icon-key"></i>
                    <span>Reset Password</span>
                </a>
                <a href="{{ route('audit-logs.index') }}" class="nav-item {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                    <i class="icon-activity"></i>
                    <span>Audit Log</span>
                </a>
                <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="icon-settings"></i>
                    <span>Pengaturan</span>
                </a>
            @endif
        @endauth
    </nav>
</aside>
