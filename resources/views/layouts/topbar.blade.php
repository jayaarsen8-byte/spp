<header class="app-topbar">
    <div class="topbar-left">
        @if(request()->routeIs('pos.*'))
            <h2>Point of Sale</h2>
        @else
            <nav class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @if(request()->routeIs('products.*'))
                    <span>/</span>
                    <span>Produk</span>
                @elseif(request()->routeIs('customers.*'))
                    <span>/</span>
                    <span>Pelanggan</span>
                @elseif(request()->routeIs('sales.*'))
                    <span>/</span>
                    <span>Penjualan</span>
                @elseif(request()->routeIs('receivables.*'))
                    <span>/</span>
                    <span>Piutang</span>
                @elseif(request()->routeIs('expenses.*'))
                    <span>/</span>
                    <span>Pengeluaran</span>
                @elseif(request()->routeIs('reports.*'))
                    <span>/</span>
                    <span>Laporan</span>
                @endif
            </nav>
        @endif
    </div>
    <div class="topbar-right">
        <button class="btn-icon" id="notification-btn" data-toggle="tooltip" title="Notifications">
            <i class="icon-bell"></i>
            <span class="notification-badge" id="notification-count" style="display: none;">0</span>
        </button>
        <div class="profile-menu">
            <button class="btn-profile" data-toggle="dropdown">
                <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="profile-name">{{ auth()->user()->name }}</span>
                <i class="icon-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small class="text-muted">{{ auth()->user()->role }}</small>
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="icon-user"></i> Profil
                </a>
                <a href="{{ route('notifications.index') }}" class="dropdown-item">
                    <i class="icon-bell"></i> Notifikasi
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="icon-log-out"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
