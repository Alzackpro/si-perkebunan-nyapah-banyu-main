@extends('layouts.app')

@section('title', 'Dashboard Lada')

@section('styles')
<style>
    /* Theme overrides for Lada theme */
    :root {
        --primary-green: #065f46;
        --emerald-green: #10b981;
        --light-green: #f0fdf4;
        --mint-green: #d1fae5;
        --accent-green: #059669;
    }

    .sidebar-link.active {
        color: #065f46 !important;
        background-color: #d1fae5 !important;
        border-left: 4px solid #10b981 !important;
    }

    .btn-add-land {
        background-color: #065f46 !important;
    }
    .btn-add-land:hover {
        background-color: #047857 !important;
    }

    .text-success {
        color: #065f46 !important;
    }

    .lada-banner {
        background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
        border-radius: 20px;
        padding: 40px;
        color: #ffffff;
        margin-bottom: 30px;
        position: relative;
        box-shadow: 0 4px 20px rgba(6, 95, 70, 0.15);
    }

    .stat-card-lada {
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        transition: all 0.3s;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-card-lada:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(6, 95, 70, 0.05);
    }

    .stat-card-lada .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background-color: var(--light-green);
        color: var(--primary-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-card-lada .label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .stat-card-lada .value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        font-family: 'Outfit', sans-serif;
        margin-top: 2px;
    }

    .status-filter-btn {
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 30px;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #64748b;
        transition: all 0.2s;
        text-decoration: none;
    }

    .status-filter-btn.active, .status-filter-btn:hover {
        background-color: var(--light-green);
        color: var(--primary-green);
        border-color: var(--mint-green);
    }

    /* Satellite Map Widget styling */
    .map-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        position: relative;
        height: 200px;
        background-color: #e2e8f0;
    }

    .map-overlay-label {
        position: absolute;
        bottom: 12px;
        left: 12px;
        background: rgba(15, 23, 42, 0.85);
        color: #ffffff;
        font-size: 0.72rem;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .progress-bar-custom {
        height: 8px;
        border-radius: 10px;
        background-color: #f1f5f9;
        overflow: hidden;
    }

    .progress-fill-custom {
        height: 100%;
        background-color: var(--primary-green);
        border-radius: 10px;
    }

    .badge-status {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        text-transform: uppercase;
    }
    .badge-produktif { background-color: #d1fae5; color: #065f46; }
    .badge-replanting { background-color: #fee2e2; color: #991b1b; }

    .action-btn-view {
        color: #0d9488;
        background: none;
        border: none;
        padding: 6px;
        font-size: 1.05rem;
    }
    .action-btn-edit {
        color: #64748b;
        background: none;
        border: none;
        padding: 6px;
        font-size: 1.05rem;
    }
</style>
@endsection

@section('content')
<!-- Lada Header Banner -->
<div class="lada-banner">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h2 class="m-0 text-white fw-bold">Dashboard Lada</h2>
            <p class="m-0 opacity-90">Ringkasan Utama - Pantau seluruh aktivitas operasional, mulai dari pembibitan, perawatan, hingga hasil panen dan pengiriman Lada.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge p-3 rounded-pill bg-white text-dark fw-bold fs-7 shadow-sm">
                <i class="fa-solid fa-chart-line text-success me-1"></i> Data Operasional Lada Terkini
            </span>
        </div>
    </div>
</div>

<!-- Stats Card Row -->
<div class="row mb-4">
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-lada">
            <div class="icon-box">
                <i class="fa-solid fa-leaf"></i>
            </div>
            <div>
                <p class="label">Total Luas Lahan</p>
                <div class="value">{{ number_format($totalLuas, 1, ',', '.') }} HA</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-lada">
            <div class="icon-box" style="background-color: #fffbeb; color: #d97706;">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <div>
                <p class="label">Total Panen (Kg)</p>
                <div class="value">{{ number_format($totalPanen, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-lada">
            <div class="icon-box" style="background-color: #eff6ff; color: #2563eb;">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <div>
                <p class="label">Pengiriman (Kg)</p>
                <div class="value">{{ number_format($totalPengiriman, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-lada" style="background-color: #065f46; color: #ffffff;">
            <div class="icon-box" style="background-color: #047857; color: #ffffff;">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="label" style="color: #d1fae5;">Upah SDM</p>
                <div class="value" style="color: #ffffff;">Rp {{ number_format($totalSdm, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Section: Aktivitas Kebun -->
<div class="row">
    <!-- Stok Pembibitan -->
    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-column text-center justify-content-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 50%;">
                    <i class="fa-solid fa-seedling fs-3"></i>
                </div>
                <h6 class="fw-bold mb-1 text-dark">Stok Pembibitan</h6>
                <p class="text-muted mb-2" style="font-size: 0.8rem;">Pengadaan bibit</p>
                <h3 class="fw-bold text-success mb-0">{{ number_format($totalPembibitan, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Bibit</span></h3>
            </div>
        </div>
    </div>

    <!-- Aktivitas Penanaman -->
    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-column text-center justify-content-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 50%;">
                    <i class="fa-solid fa-plant-wilt fs-3"></i>
                </div>
                <h6 class="fw-bold mb-1 text-dark">Total Penanaman</h6>
                <p class="text-muted mb-2" style="font-size: 0.8rem;">Bibit ditanam</p>
                <h3 class="fw-bold text-primary mb-0">{{ number_format($totalTanam, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Pohon</span></h3>
            </div>
        </div>
    </div>

    <!-- Aktivitas Pemupukan -->
    <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-column text-center justify-content-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-info-subtle text-info mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 50%;">
                    <i class="fa-solid fa-vial fs-3"></i>
                </div>
                <h6 class="fw-bold mb-1 text-dark">Pemupukan</h6>
                <p class="text-muted mb-2" style="font-size: 0.8rem;">Frekuensi pupuk</p>
                <h3 class="fw-bold text-info mb-0">{{ number_format($totalPemupukan, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Kali</span></h3>
            </div>
        </div>
    </div>

    <!-- Aktivitas Obat Hama -->
    <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-column text-center justify-content-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 50%;">
                    <i class="fa-solid fa-bug-slash fs-3"></i>
                </div>
                <h6 class="fw-bold mb-1 text-dark">Obat Hama</h6>
                <p class="text-muted mb-2" style="font-size: 0.8rem;">Frekuensi obat hama</p>
                <h3 class="fw-bold text-danger mb-0">{{ number_format($totalObatHama, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Kali</span></h3>
            </div>
        </div>
    </div>
</div>
@endsection
