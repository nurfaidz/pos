<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('dashboard') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @if (auth()->user()->role == 'admin')
            <li class="nav-item nav-category">Master Data</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/category') }}">
                    <i class="menu-icon mdi mdi-floor-plan"></i>
                    <span class="menu-title">Kategori</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/product') }}">
                    <i class="menu-icon mdi mdi-floor-plan"></i>
                    <span class="menu-title">Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/member') }}">
                    <i class="menu-icon mdi mdi-floor-plan"></i>
                    <span class="menu-title">Member</span>
                </a>
            </li>
            <li class="nav-item nav-category">Transaction</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/expend') }}">
                    <i class="menu-icon mdi mdi-card-text-outline"></i>
                    <span class="menu-title">Pengeluaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/sale') }}">
                    <i class="menu-icon mdi mdi-card-text-outline"></i>
                    <span class="menu-title">Penjualan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transaction.new') }}">
                    <i class="menu-icon mdi mdi-chart-line"></i>
                    <span class="menu-title">Transaksi Baru</span>
                </a>
            </li>
            <li class="nav-item nav-category">Report</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('report.index') }}">
                    <i class="menu-icon mdi mdi-file-document"></i>
                    <span class="menu-title">Laporan</span>
                </a>
            </li>
            <li class="nav-item nav-category">System</li>
            <li class="nav-item">
                <a class="nav-link"href="{{ url('/user') }}">
                    <i class="menu-icon mdi mdi-account-circle-outline"></i>
                    <span class="menu-title">User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"href="{{ url('/setting') }}">
                    <i class="menu-icon mdi mdi-account-circle-outline"></i>
                    <span class="menu-title">Pengaturan</span>
                </a>
            </li>
            <li class="nav-item nav-category">Archive</li>
            <li class="nav-item">
                <a class="nav-link"href="{{ route('expend-archive') }}">
                    <i class="menu-icon mdi mdi-account-circle-outline"></i>
                    <span class="menu-title">Pengeluaran</span>
                </a>
            </li>
        @else
            <li class="nav-item nav-category">Transaction</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transaction.new') }}">
                    <i class="menu-icon mdi mdi-chart-line"></i>
                    <span class="menu-title">Transaksi Baru</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
