@php
    $avatarUrl = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'; // default owner (Haji Banyu)
    if (!Auth::user()->isOwner()) {
        if (str_contains(Auth::user()->email, 'kopi')) {
            $avatarUrl = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'; // Andi Wijaya
        } elseif (str_contains(Auth::user()->email, 'lada')) {
            $avatarUrl = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'; // Siti Aminah
        } else {
            $avatarUrl = 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'; // Ahmad
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Nyapah Banyu Plantation</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css">

    <!-- Custom Styling (Aesthetically Rich Light Sidebar & Green Accents) -->
    <style>
        :root {
            --primary-green: #0a3d24;
            --emerald-green: #15803d;
            --light-green: #f0fdf4;
            --mint-green: #bbf7d0;
            --accent-green: #22c55e;
            --dark-charcoal: #1e293b;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: var(--dark-charcoal);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .brand-text {
            font-family: 'Outfit', sans-serif;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar-brand {
            padding: 24px 24px 10px 24px;
        }

        .sidebar-brand-title {
            color: var(--primary-green);
            font-weight: 800;
            font-size: 1.4rem;
            line-height: 1.1;
            margin: 0;
        }

        .sidebar-brand-subtitle {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 2px;
        }

        .sidebar-top-section {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            min-height: 0;
        }

        .sidebar-menu-scroll {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px 0;
        }

        .sidebar-header {
            padding: 15px 25px 5px 25px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            font-weight: 700;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            margin: 4px 15px;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.92rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: var(--primary-green);
            background-color: #f1f5f9;
        }

        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            opacity: 0.8;
        }

        .sidebar-link.active {
            color: #065f46;
            background-color: #d1fae5;
            font-weight: 600;
            border-left: 4px solid var(--accent-green);
        }

        /* Sidebar Bottom Actions */
        .sidebar-bottom-actions {
            padding: 15px;
            border-top: 1px solid #f1f5f9;
            background-color: #ffffff;
        }

        .btn-add-land {
            display: block;
            width: 100%;
            background-color: var(--primary-green);
            color: #ffffff;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            margin-bottom: 12px;
            transition: all 0.2s;
            border: none;
        }

        .btn-add-land:hover {
            background-color: #0f5132;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(10, 61, 36, 0.15);
        }

        .sidebar-action-link {
            display: flex;
            align-items: center;
            padding: 8px 15px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .sidebar-action-link:hover {
            color: var(--primary-green);
            background-color: #f8fafc;
        }

        .sidebar-action-link i {
            margin-right: 10px;
            font-size: 1rem;
        }

        /* Owner Profile Badge Bottom Left */
        .owner-profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-top: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .owner-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--mint-green);
        }

        .owner-details {
            line-height: 1.2;
        }

        .owner-name {
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--dark-charcoal);
            margin: 0;
        }

        .owner-role {
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Content Area Wrapper */
        #content-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        /* Top Navbar */
        .top-navbar {
            background-color: #ffffff;
            height: 70px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            border-bottom: 1px solid #e2e8f0;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Modern Search Input */
        .navbar-search-wrapper {
            position: relative;
            width: 320px;
        }

        .navbar-search-input {
            width: 100%;
            height: 38px;
            padding: 8px 16px 8px 40px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 0.88rem;
            color: var(--dark-charcoal);
            outline: none;
            transition: all 0.2s;
        }

        .navbar-search-input:focus {
            background-color: #ffffff;
            border-color: var(--emerald-green);
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
        }

        .navbar-search-icon {
            position: absolute;
            left: 15px;
            top: 11px;
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .navbar-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
        }

        .navbar-icon-btn:hover {
            background-color: #f1f5f9;
            color: var(--dark-charcoal);
        }

        .navbar-icon-btn .badge-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }

        .navbar-user-header {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar-user-label {
            text-align: right;
            line-height: 1.2;
        }

        .navbar-user-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--dark-charcoal);
            margin: 0;
        }

        .navbar-user-role {
            font-size: 0.72rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
        }

        .navbar-user-pic {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--mint-green);
        }

        /* Main Content Container */
        .main-content {
            padding: 30px;
            flex-grow: 1;
        }

        /* Footer styling */
        footer {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 20px 30px;
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Modern Card Styling */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.01);
            background-color: #ffffff;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 24px;
            font-weight: 700;
            color: var(--primary-green);
            font-size: 1rem;
        }

        /* Button Styling */
        .btn-success {
            background-color: var(--emerald-green);
            border-color: var(--emerald-green);
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.2s;
        }

        .btn-success:hover {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.15);
        }

        .btn-outline-success {
            color: var(--emerald-green);
            border-color: var(--emerald-green);
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-outline-success:hover {
            background-color: var(--emerald-green);
            color: #ffffff;
        }

        /* Table custom scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        /* Notifications Panel Styling */
        .notification-item {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .notification-item.unread {
            background-color: var(--light-green) !important;
        }
        .notification-item.unread:hover {
            background-color: var(--mint-green) !important;
        }
        .notification-item.read {
            background-color: #ffffff !important;
            opacity: 0.75;
        }
        .notification-item.read:hover {
            background-color: #f8fafc !important;
            opacity: 1;
        }
        .notification-item.read .notif-title {
            font-weight: 500 !important;
            color: #64748b !important;
        }
        .notification-item.read .unread-dot {
            display: none !important;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- SIDEBAR -->
    <div id="sidebar">
        @if(Auth::user()->isOwner())
            <div class="sidebar-top-section">
                <div class="sidebar-brand">
                    <h3 class="sidebar-brand-title">Nyapah Banyu</h3>
                    <div class="sidebar-brand-subtitle">Sistem Perkebunan</div>
                </div>
                
                <div class="sidebar-menu-scroll">
                    <div class="sidebar-header">Utama</div>
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard
                    </a>

                    <div class="sidebar-header">Data Master</div>
                    <a href="{{ route('komoditas.index') }}" class="sidebar-link {{ Request::is('komoditas*') ? 'active' : '' }}">
                        <i class="fa-brands fa-envira"></i> Data Komoditas
                    </a>
                    <a href="{{ route('peralatan.index') }}" class="sidebar-link {{ Request::is('peralatan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-toolbox"></i> Data Peralatan
                    </a>
                    <a href="{{ route('perlengkapan.index') }}" class="sidebar-link {{ Request::is('perlengkapan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked"></i> Data Perlengkapan
                    </a>
                    <a href="{{ route('jadwal.index') }}" class="sidebar-link {{ Request::is('jadwal*') ? 'active' : '' }}">
                        <i class="fa-regular fa-calendar-days"></i> Data Jadwal
                    </a>
                    <a href="{{ route('status.index') }}" class="sidebar-link {{ Request::is('status*') ? 'active' : '' }}">
                        <i class="fa-solid fa-tags"></i> Data Status
                    </a>
                    <a href="{{ route('area-kebun.index') }}" class="sidebar-link {{ Request::is('area-kebun*') ? 'active' : '' }}">
                        <i class="fa-solid fa-map-location-dot"></i> Area Kebun
                    </a>
                    <a href="{{ route('pegawai.index') }}" class="sidebar-link {{ Request::is('pegawai*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> Data Pegawai
                    </a>
                    <a href="{{ route('user.index') }}" class="sidebar-link {{ Request::is('user*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-lock"></i> Akun Pengguna
                    </a>

                    <div class="sidebar-header">Aktivitas Kebun</div>
                    <a href="{{ route('pembibitan.index') }}" class="sidebar-link {{ Request::is('pembibitan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-seedling"></i> Pembibitan
                    </a>
                    <a href="{{ route('tanam.index') }}" class="sidebar-link {{ Request::is('tanam*') ? 'active' : '' }}">
                        <i class="fa-solid fa-plant-wilt"></i> Tanam & Penyulaman
                    </a>
                    <a href="{{ route('operasional.index') }}" class="sidebar-link {{ Request::is('operasional*') ? 'active' : '' }}">
                        <i class="fa-solid fa-screwdriver-wrench"></i> Operasional Lahan
                    </a>
                    <a href="{{ route('panen.index') }}" class="sidebar-link {{ Request::is('panen*') ? 'active' : '' }}">
                        <i class="fa-solid fa-wheat-awn"></i> Data Panen
                    </a>
                    <a href="{{ route('sdm.index') }}" class="sidebar-link {{ Request::is('sdm*') ? 'active' : '' }}">
                        <i class="fa-solid fa-people-carry-box"></i> Gaji Buruh/SDM
                    </a>

                    <div class="sidebar-header">Manajemen Hasil</div>
                    <a href="{{ route('pengiriman.index') }}" class="sidebar-link {{ Request::is('pengiriman*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-ramp-box"></i> Pengiriman Pabrik
                    </a>

                    <div class="sidebar-header">Pelaporan & Sistem</div>
                    <a href="{{ route('laporan.index') }}" class="sidebar-link {{ Request::is('laporan*') || Request::is('log-aktivitas*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Pelaporan & Sistem
                    </a>
                </div>
            </div>

            <!-- Sidebar Bottom Actions for Owner -->
            <div class="sidebar-bottom-actions">
                <a href="{{ route('area-kebun.create') }}" class="btn-add-land">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Lahan Baru
                </a>
                <a href="{{ route('settings') }}" class="sidebar-action-link {{ Request::is('settings*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Pengaturan
                </a>

                <!-- Owner Profile Card -->
                <div class="owner-profile-card">
                    <img src="{{ $avatarUrl }}" alt="Owner avatar" class="owner-avatar">
                    <div class="owner-details">
                        <div class="owner-name">{{ Auth::user()->name }}</div>
                        <div class="owner-role">Pemilik Kebun</div>
                    </div>
                </div>
            </div>
        @elseif(str_contains(Auth::user()->email, 'kopi'))
            <!-- SIDEBAR FOR PENGURUS KEBUN KOPI -->
            <div class="sidebar-top-section">
                <div class="sidebar-brand">
                    <h3 class="sidebar-brand-title">Nyapah Banyu</h3>
                    <div class="sidebar-brand-subtitle">Pengelolaan Perkebunan</div>
                </div>
                
                <div class="sidebar-menu-scroll">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ Request::is('dashboard') || Request::is('area-kebun*') ? 'active' : '' }}">
                        <i class="fa-solid fa-mug-hot"></i> Dashboard Kopi
                    </a>

                    <a href="{{ route('panen.index') }}" class="sidebar-link {{ Request::is('panen*') ? 'active' : '' }}">
                        <i class="fa-solid fa-scale-balanced"></i> Input Panen Kopi
                    </a>

                    <a href="{{ route('operasional.index') }}" class="sidebar-link {{ Request::is('operasional*') ? 'active' : '' }}">
                        <i class="fa-solid fa-screwdriver-wrench"></i> Operasional Kopi
                    </a>

                    <a href="{{ route('pembibitan.index') }}" class="sidebar-link {{ Request::is('pembibitan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-seedling"></i> Pembibitan
                    </a>

                    <a href="{{ route('tanam.index') }}" class="sidebar-link {{ Request::is('tanam*') ? 'active' : '' }}">
                        <i class="fa-solid fa-plant-wilt"></i> Tanam & Penyulaman
                    </a>


                    <a href="{{ route('sdm.index') }}" class="sidebar-link {{ Request::is('sdm*') ? 'active' : '' }}">
                        <i class="fa-solid fa-people-carry-box"></i> Upah Pekerja Kopi
                    </a>

                    <div class="sidebar-header">Manajemen Hasil</div>
                    <a href="{{ route('pengiriman.index') }}" class="sidebar-link {{ Request::is('pengiriman*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-ramp-box"></i> Pengiriman Pabrik
                    </a>
                </div>
            </div>

            <!-- Sidebar Bottom Actions for Pengurus Kopi -->
            <div class="sidebar-bottom-actions">
                <div class="d-flex flex-column gap-2 mb-3">
                    <a href="{{ route('laporan.index') }}" class="btn-add-land text-center">
                        <i class="fa-solid fa-file-lines me-1"></i> Lihat Laporan
                    </a>
                </div>
                <a href="{{ route('settings') }}" class="sidebar-action-link {{ Request::is('settings*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Pengaturan
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();" class="sidebar-action-link text-danger">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </a>
                <form id="logout-form-sidebar" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>
            </div>
        @elseif(str_contains(Auth::user()->email, 'lada'))
            <!-- SIDEBAR FOR PENGURUS KEBUN LADA -->
            <div class="sidebar-top-section">
                <div class="sidebar-brand">
                    <h3 class="sidebar-brand-title">Nyapah Banyu</h3>
                    <div class="sidebar-brand-subtitle">Pengurus Kebun Lada</div>
                </div>
                
                <div class="sidebar-menu-scroll">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ Request::is('dashboard') || Request::is('area-kebun*') ? 'active' : '' }}">
                        <i class="fa-solid fa-border-all"></i> Dashboard Lada
                    </a>

                    <a href="{{ route('panen.index') }}" class="sidebar-link {{ Request::is('panen*') ? 'active' : '' }}">
                        <i class="fa-solid fa-scale-balanced"></i> Input Panen Lada
                    </a>

                    <a href="{{ route('operasional.index') }}" class="sidebar-link {{ Request::is('operasional*') ? 'active' : '' }}">
                        <i class="fa-solid fa-screwdriver-wrench"></i> Operasional Lada
                    </a>

                    <a href="{{ route('pembibitan.index') }}" class="sidebar-link {{ Request::is('pembibitan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-seedling"></i> Pembibitan
                    </a>

                    <a href="{{ route('tanam.index') }}" class="sidebar-link {{ Request::is('tanam*') ? 'active' : '' }}">
                        <i class="fa-solid fa-plant-wilt"></i> Tanam & Penyulaman
                    </a>


                    <a href="{{ route('sdm.index') }}" class="sidebar-link {{ Request::is('sdm*') ? 'active' : '' }}">
                        <i class="fa-solid fa-people-carry-box"></i> Upah Pekerja Lada
                    </a>

                    <div class="sidebar-header">Manajemen Hasil</div>
                    <a href="{{ route('pengiriman.index') }}" class="sidebar-link {{ Request::is('pengiriman*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-ramp-box"></i> Pengiriman Pabrik
                    </a>
                </div>
            </div>

            <!-- Sidebar Bottom Actions for Pengurus Lada -->
            <div class="sidebar-bottom-actions">
                <div class="d-flex flex-column gap-2 mb-3">
                    <a href="{{ route('laporan.index') }}" class="btn-add-land text-center">
                        <i class="fa-solid fa-file-lines me-1"></i> Lihat Laporan
                    </a>
                </div>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();" class="sidebar-action-link text-danger">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </a>
                <form id="logout-form-sidebar-lada" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>

                <!-- Profile Card matching the bottom of the mockup -->
                <div class="owner-profile-card">
                    <img src="{{ $avatarUrl }}" alt="User avatar" class="owner-avatar">
                    <div class="owner-details">
                        <div class="owner-name">{{ Auth::user()->name }}</div>
                        <div class="owner-role">PENGURUS KEBUN LADA</div>
                    </div>
                </div>
            </div>
        @else
            <!-- SIDEBAR FOR PENGURUS KEBUN SAWIT -->
            <div>
                <div class="sidebar-brand">
                    <h3 class="sidebar-brand-title">Nyapah Banyu</h3>
                    <div class="sidebar-brand-subtitle">Pengurus Kebun Sawit</div>
                </div>
                
                <div class="sidebar-menu-scroll">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ Request::is('dashboard') || Request::is('area-kebun*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard Sawit
                    </a>

                    <a href="{{ route('panen.index') }}" class="sidebar-link {{ Request::is('panen*') ? 'active' : '' }}">
                        <i class="fa-solid fa-wheat-awn"></i> Input Panen Sawit
                    </a>

                    <a href="{{ route('operasional.index') }}" class="sidebar-link {{ Request::is('operasional*') ? 'active' : '' }}">
                        <i class="fa-solid fa-screwdriver-wrench"></i> Operasional Sawit
                    </a>

                    <a href="{{ route('pembibitan.index') }}" class="sidebar-link {{ Request::is('pembibitan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-seedling"></i> Pembibitan
                    </a>

                    <a href="{{ route('tanam.index') }}" class="sidebar-link {{ Request::is('tanam*') ? 'active' : '' }}">
                        <i class="fa-solid fa-plant-wilt"></i> Tanam & Penyulaman
                    </a>


                    <a href="{{ route('sdm.index') }}" class="sidebar-link {{ Request::is('sdm*') ? 'active' : '' }}">
                        <i class="fa-solid fa-people-carry-box"></i> Upah Pekerja Sawit
                    </a>

                    <div class="sidebar-header">Manajemen Hasil</div>
                    <a href="{{ route('pengiriman.index') }}" class="sidebar-link {{ Request::is('pengiriman*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-ramp-box"></i> Pengiriman Pabrik
                    </a>
                </div>
            </div>

            <!-- Sidebar Bottom Actions for Pengurus -->
            <div class="sidebar-bottom-actions">
                <div class="d-flex flex-column gap-2 mb-3">
                    <a href="{{ route('laporan.index') }}" class="btn-add-land text-center">
                        <i class="fa-solid fa-file-lines me-1"></i> Lihat Laporan
                    </a>
                </div>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();" class="sidebar-action-link text-danger">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </a>
                <form id="logout-form-sidebar" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT WRAPPER -->
    <div id="content-wrapper">
        <!-- TOP NAVBAR -->
        <nav class="top-navbar">
            <!-- Search bar -->
            <div class="navbar-search-wrapper d-none d-md-block">
                <i class="fa-solid fa-magnifying-glass navbar-search-icon"></i>
                <input type="text" id="global-search-input" class="navbar-search-input" placeholder="{{ Request::is('area-kebun*') ? 'Cari sektor atau blok...' : (Request::is('panen*') || Request::is('pengiriman*') ? 'Cari laporan atau status pabrik...' : 'Cari aktivitas atau komoditas...') }}">
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications Bell -->
                <div class="position-relative" id="notifications-wrapper">
                    <button class="navbar-icon-btn border-0 bg-transparent p-0 position-relative" id="btn-notifications">
                        <i class="fa-solid fa-bell"></i>
                        <span class="badge-dot"></span>
                    </button>
                    <!-- Notification Pop-out Card -->
                    <div id="notifications-popout" class="card shadow-lg border-0 d-none" style="position: absolute; right: 0; top: 45px; width: 320px; z-index: 1050; border-radius: 12px; overflow: hidden;">
                        <div class="card-header bg-success text-white py-2 px-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold" style="font-size: 0.9rem;"><i class="fa-solid fa-bell me-1"></i> Aktivitas Terbaru</span>
                            @php
                                $notifQuery = \App\Models\LogAktivitas::query();
                                if (Auth::user()->isPengurus()) {
                                    $notifQuery->where('user_id', Auth::id());
                                }
                                $notifs = $notifQuery->orderBy('created_at', 'desc')->take(5)->get();
                            @endphp
                            <span class="badge bg-white text-success rounded-pill" id="notif-count-badge" style="font-size: 0.75rem;">{{ $notifs->count() }} Aktivitas</span>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto; font-size: 0.85rem;">
                            @forelse($notifs as $notif)
                                <div class="list-group-item p-3 list-group-item-action border-0 border-bottom text-start notification-item unread">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold text-success notif-title" style="font-size: 0.85rem;">
                                            <i class="fa-solid fa-circle-info me-1"></i> {{ $notif->aktivitas }}
                                        </h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans(null, true) }}</small>
                                    </div>
                                    <p class="mb-1 text-muted" style="font-size: 0.8rem; line-height: 1.3; font-weight: normal; margin: 0;">{{ $notif->deskripsi }}</p>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="fa-solid fa-bell-slash mb-2" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0 small">Belum ada aktivitas baru</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="card-footer text-center py-2 bg-light" style="font-size: 0.8rem;">
                            <a href="#" class="text-success fw-semibold text-decoration-none">Lihat Riwayat Lengkap</a>
                        </div>
                    </div>
                </div>
                
                <!-- Settings Gear -->
                <a href="{{ route('settings') }}" class="navbar-icon-btn {{ Request::is('settings*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i>
                </a>

                <!-- User profile badge -->
                <div class="navbar-user-header border-start ps-3">
                    <div class="navbar-user-label d-none d-sm-block">
                        <div class="navbar-user-name">{{ Auth::user()->name }}</div>
                        <div class="navbar-user-role">
                            @if(Auth::user()->isOwner())
                                Pemilik
                            @elseif(str_contains(Auth::user()->email, 'kopi'))
                                Pengurus Kopi
                            @elseif(str_contains(Auth::user()->email, 'lada'))
                                Pengurus Lada
                            @else
                                Pengurus Sawit
                            @endif
                        </div>
                    </div>
                    <img src="{{ $avatarUrl }}" alt="User avatar" class="navbar-user-pic">
                </div>

                <!-- Form Logout -->
                <form method="POST" action="{{ route('logout') }}" class="m-0 border-start ps-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </nav>

        <!-- CONTENT -->
        <div class="main-content">
            @yield('content')
        </div>

        <!-- FOOTER -->
        <footer class="d-flex justify-content-between align-items-center">
            <span>© 2026 Perkebunan Nyapah Banyu, Lampung Utara. Hak Cipta Dilindungi.</span>
            <span class="d-none d-md-inline">Sistem Pengelolaan Kopi, Sawit, & Lada</span>
        </footer>
    </div>

    <!-- CORE JS SCRIPTS -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <!-- SweetAlert Notifications Listener -->
    <script>
        $(document).ready(function() {
            // DataTables default config
            if($('.datatable').length > 0) {
                $('.datatable').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' // Lokalisasi Bahasa Indonesia
                    }
                });
            }

            // Session Success Alert
            @if(session('sukses'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('sukses') }}",
                    confirmButtonColor: '#198754',
                    timer: 3500
                });
            @endif

            // Session Gagal Alert
            @if(session('gagal'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Tindakan!',
                    text: "{{ session('gagal') }}",
                    confirmButtonColor: '#d33',
                    timer: 4500
                });
            @endif

            // --- Notification System JS ---
            function getReadNotifications() {
                try {
                    return JSON.parse(localStorage.getItem('read_notifications') || '[]');
                } catch(e) {
                    return [];
                }
            }

            function setReadNotifications(list) {
                localStorage.setItem('read_notifications', JSON.stringify(list));
            }

            function updateNotificationsUI() {
                var readNotifs = getReadNotifications();
                var unreadCount = 0;

                $('.notification-item').each(function() {
                    var id = $(this).attr('data-id');
                    if (readNotifs.indexOf(id) !== -1) {
                        $(this).removeClass('unread').addClass('read');
                    } else {
                        $(this).removeClass('read').addClass('unread');
                        unreadCount++;
                    }
                });

                // Update counts & badges
                if (unreadCount > 0) {
                    $('#notif-count-badge').text(unreadCount + ' Baru').show();
                    $('.badge-dot').show();
                } else {
                    $('#notif-count-badge').text('0 Baru');
                    $('.badge-dot').hide();
                }
            }

            // Init state
            updateNotificationsUI();

            // Toggle Notifications Popout
            $('#btn-notifications').on('click', function(e) {
                e.stopPropagation();
                $('#notifications-popout').toggleClass('d-none');
            });

            // Close Notifications if clicked outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#notifications-wrapper').length) {
                    $('#notifications-popout').addClass('d-none');
                }
            });

            // Mark single notification as read
            $('.notification-item').on('click', function(e) {
                var id = $(this).attr('data-id');
                var readNotifs = getReadNotifications();
                if (readNotifs.indexOf(id) === -1) {
                    readNotifs.push(id);
                    setReadNotifications(readNotifs);
                    updateNotificationsUI();
                }
            });

            // Mark all notifications as read
            $('#mark-all-read').on('click', function(e) {
                e.preventDefault();
                var allIds = [];
                $('.notification-item').each(function() {
                    allIds.push($(this).attr('data-id'));
                });
                setReadNotifications(allIds);
                updateNotificationsUI();
            });

            // --- Global Search Redirect Logic ---
            $('#global-search-input').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    var query = $(this).val().toLowerCase().trim();
                    if (!query) return;

                    var routes = {
                        'panen': '{{ route("panen.index") }}',
                        'data panen': '{{ route("panen.index") }}',
                        'hasil': '{{ route("panen.index") }}',
                        'lahan': '{{ route("area-kebun.index") }}',
                        'kebun': '{{ route("area-kebun.index") }}',
                        'area': '{{ route("area-kebun.index") }}',
                        'bibit': '{{ route("pembibitan.index") }}',
                        'pembibitan': '{{ route("pembibitan.index") }}',
                        'operasional': '{{ route("operasional.index") }}',
                        'biaya': '{{ route("operasional.index") }}',
                        'sdm': '{{ route("sdm.index") }}',
                        'gaji': '{{ route("sdm.index") }}',
                        'pekerja': '{{ route("sdm.index") }}',
                        'buruh': '{{ route("sdm.index") }}',
                        'pengiriman': '{{ route("pengiriman.index") }}',
                        'pabrik': '{{ route("pengiriman.index") }}',
                        'laporan': '{{ route("laporan.index") }}',
                        'log': '{{ route("log-aktivitas.index") }}',
                        'aktivitas': '{{ route("log-aktivitas.index") }}',
                        'pengaturan': '{{ route("settings") }}',
                        'settings': '{{ route("settings") }}',
                        'profile': '{{ route("settings") }}'
                    };

                    // Check exact or partial matches
                    var redirectUrl = null;
                    for (var keyword in routes) {
                        if (query.indexOf(keyword) !== -1 || keyword.indexOf(query) !== -1) {
                            redirectUrl = routes[keyword];
                            break;
                        }
                    }

                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Pencarian Aktivitas',
                            text: 'Aktivitas "' + $(this).val() + '" tidak ditemukan. Coba ketik "panen", "lahan", "pembibitan", "operasional", atau "gaji".',
                            confirmButtonColor: '#198754'
                        });
                    }
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
