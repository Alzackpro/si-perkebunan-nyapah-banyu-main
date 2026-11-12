@extends('layouts.app')

@section('title', 'Dashboard Sawit')

@section('styles')
<style>
    /* Premium style for Area Kebun Sawit */
    .stat-card-block {
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-card-block.highlight {
        background-color: #0a3d24;
        color: #ffffff;
        border: none;
    }

    .stat-card-block.highlight .metric-lbl {
        color: #a7f3d0;
    }

    .stat-card-block.highlight .metric-val {
        color: #ffffff;
    }

    .stat-card-block .metric-lbl {
        font-size: 0.78rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
    }

    .stat-card-block .metric-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 4px;
        font-family: 'Outfit', sans-serif;
    }

    .stat-card-block .metric-badge {
        font-size: 0.78rem;
        font-weight: 700;
    }

    .sawit-banner {
        background: linear-gradient(135deg, #0a3d24 0%, #105935 100%);
        border-radius: 20px;
        padding: 40px;
        color: #ffffff;
        margin-bottom: 30px;
        position: relative;
        box-shadow: 0 4px 20px rgba(10, 61, 36, 0.15);
    }

    /* Tab Filter */
    .filter-tab-btn {
        padding: 8px 18px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #475569;
        font-size: 0.88rem;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .filter-tab-btn:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }

    .filter-tab-btn.active {
        background-color: #0a3d24;
        border-color: #0a3d24;
        color: #ffffff;
    }

    /* Table custom designs */
    .table-blocks th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #475569;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        background-color: #f8fafc;
    }

    .table-blocks td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }

    .avatar-block-code {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background-color: #f1f5f9;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
    }

    .table-blocks tr:hover td {
        background-color: #f8fafc;
    }

    .badge-status-sawit {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 30px;
        text-transform: uppercase;
    }

    .badge-status-sawit.harvesting {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-status-sawit.growing {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-status-sawit.replanting {
        background-color: #ffe4e6;
        color: #9f1239;
    }

    /* Map Card */
    .map-card-sawit {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
    }

    .map-illustration-box {
        background-color: #f1f9f6;
        border-radius: 12px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px dashed #cbd5e1;
        position: relative;
        overflow: hidden;
    }

    /* Production trend chart card */
    .trend-card-sawit {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
    }

    @media (min-width: 768px) {
        .table-responsive {
            overflow: visible !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Sawit Header Banner -->
<div class="sawit-banner">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h2 class="m-0 text-white fw-bold">Dashboard Sawit</h2>
            <p class="m-0 opacity-90">Ringkasan Utama - Pantau seluruh aktivitas operasional, mulai dari pembibitan, perawatan, hingga hasil panen dan pengiriman Sawit.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge p-3 rounded-pill bg-white text-dark fw-bold fs-7 shadow-sm">
                <i class="fa-solid fa-chart-line text-success me-1"></i> Data Operasional Sawit Terkini
            </span>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <!-- Total Luas -->
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-block">
            <div>
                <span class="metric-lbl">Total Luas Lahan</span>
                <div class="metric-val">{{ number_format($totalLuas, 1, ',', '.') }} Ha</div>
            </div>
            <span class="badge bg-success-subtle text-success metric-badge">{{ $totalBlok }} Blok</span>
        </div>
    </div>

    <!-- Total Panen -->
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-block">
            <div>
                <span class="metric-lbl">Total Hasil Panen</span>
                <div class="metric-val">{{ number_format($totalPanen, 0, ',', '.') }}</div>
            </div>
            <span class="badge bg-secondary-subtle text-secondary metric-badge">Kg</span>
        </div>
    </div>

    <!-- Total Pengiriman -->
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-block">
            <div>
                <span class="metric-lbl">Total Pengiriman</span>
                <div class="metric-val">{{ number_format($totalPengiriman, 0, ',', '.') }}</div>
            </div>
            <span class="badge bg-info-subtle text-info metric-badge">Kg</span>
        </div>
    </div>

    <!-- Estimasi Panen -->
    <div class="col-md-3">
        <div class="stat-card-block highlight">
            <div>
                <span class="metric-lbl">Pengeluaran Upah SDM</span>
                <div class="metric-val">Rp {{ number_format($totalSdm, 0, ',', '.') }}</div>
            </div>
            <span class="badge bg-warning text-dark metric-badge">Total</span>
        </div>
    </div>
</div>



<!-- Bottom Section (Aktivitas Kebun) -->
<div class="row">
    <!-- Stok Pembibitan -->
    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 d-flex flex-column text-center justify-content-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 50%;">
                    <i class="fa-solid fa-seedling fs-3"></i>
                </div>
                <h6 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit';">Stok Pembibitan</h6>
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
                <h6 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit';">Total Penanaman</h6>
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
                <h6 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit';">Pemupukan</h6>
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
                <h6 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit';">Obat Hama</h6>
                <p class="text-muted mb-2" style="font-size: 0.8rem;">Frekuensi obat hama</p>
                <h3 class="fw-bold text-danger mb-0">{{ number_format($totalObatHama, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Kali</span></h3>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Scripts if needed
    });
</script>
@endsection
