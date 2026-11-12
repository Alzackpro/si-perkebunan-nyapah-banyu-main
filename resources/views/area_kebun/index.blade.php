@extends('layouts.app')

@section('title', 'Monitoring Lahan')

@section('styles')
<style>
    /* Custom Styling for Land Monitoring View */
    .land-header {
        margin-bottom: 25px;
    }

    .land-title {
        font-weight: 800;
        color: var(--primary-green);
        margin: 0;
    }

    .land-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    /* Map Card */
    .map-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
    }

    .map-container {
        position: relative;
        background-color: #d8f3dc; /* Light natural background for land map */
        border-radius: 14px;
        height: 300px;
        width: 100%;
        overflow: hidden;
        border: 1px solid #b7e4c7;
    }

    /* Land Use Card */
    .use-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .use-item {
        margin-bottom: 20px;
    }

    .use-info {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--dark-charcoal);
        margin-bottom: 6px;
    }

    .use-info span.crop-label {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .use-info span.crop-label::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .use-item.kopi .use-info span.crop-label::before { background-color: #d97706; }
    .use-item.lada .use-info span.crop-label::before { background-color: #10b981; }
    .use-item.sawit .use-info span.crop-label::before { background-color: #334155; }

    .use-progress {
        height: 10px;
        background-color: #f1f5f9;
        border-radius: 5px;
        overflow: hidden;
    }

    .use-progress-fill {
        height: 100%;
        border-radius: 5px;
    }

    .use-item.kopi .use-progress-fill { background-color: #d97706; }
    .use-item.lada .use-progress-fill { background-color: #10b981; }
    .use-item.sawit .use-progress-fill { background-color: #334155; }

    .total-area-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px 20px;
        text-align: left;
        margin-top: 15px;
    }

    .total-area-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .total-area-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--primary-green);
    }

    /* Table custom designs */
    .table-metrics th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        font-weight: 700;
        border-bottom: 2px solid #f1f5f9;
        padding: 12px 16px;
    }

    .table-metrics td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .lahan-identitas {
        line-height: 1.3;
    }

    .lahan-blok-name {
        font-weight: 700;
        color: var(--dark-charcoal);
        margin: 0;
    }

    .lahan-district {
        font-size: 0.78rem;
        color: #94a3b8;
        margin: 0;
    }

    .badge-health {
        font-size: 0.72rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 30px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-health.optimal {
        background-color: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-health.stabil {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .badge-health.perlu_tindakan {
        background-color: #fff5f5;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Productivity Column */
    .prod-trend {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .prod-trend.up { color: #166534; }
    .prod-trend.stable { color: #475569; }
    .prod-trend.down { color: #991b1b; }

    /* Footer Stats Cards */
    .footer-stat-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .footer-stat-card.dark {
        background-color: var(--primary-green);
        color: #ffffff;
        border: none;
    }

    .footer-stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .footer-stat-card.dark .footer-stat-label {
        color: #a7f3d0;
    }

    .footer-stat-value {
        font-size: 2.3rem;
        font-weight: 800;
        margin: 8px 0;
        line-height: 1.1;
    }

    .footer-stat-desc {
        font-size: 0.82rem;
        color: #64748b;
        margin: 0;
    }

    .footer-stat-card.dark .footer-stat-desc {
        color: #d1fae5;
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div class="row align-items-center mb-4 land-header">
    <div class="col">
        <h2 class="land-title">Monitoring Lahan</h2>
        <p class="land-subtitle">Ikhtisar kesehatan dan produktivitas seluruh sektor perkebunan Nyapah Banyu.</p>
    </div>
    <div class="col-auto d-flex gap-3">
        <a href="{{ route('area-kebun.create') }}" class="btn btn-success rounded-3 shadow-sm px-3" style="background-color: #059669; border-color: #059669;">
            <i class="fa-solid fa-plus me-1"></i> Tambah Lahan
        </a>
        <!-- Filter dropdown button -->
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle bg-white border-light-subtle rounded-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-filter me-1 text-muted"></i> Saring Lahan
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-menu-item dropdown-item" href="?">Semua Komoditas</a></li>
                <li><a class="dropdown-menu-item dropdown-item" href="?jenis_tanaman=kopi">Kopi</a></li>
                <li><a class="dropdown-menu-item dropdown-item" href="?jenis_tanaman=sawit">Kelapa Sawit</a></li>
                <li><a class="dropdown-menu-item dropdown-item" href="?jenis_tanaman=lada">Lada</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- MAP & LAND USE ROW -->
<div class="row mb-4">
    <!-- SEKTOR BLOK METRICS TABLE REPLACING MAP -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Metrik Kesehatan & Produktivitas Blok</span>
                <a href="{{ route('area-kebun.index') }}" class="text-success text-decoration-none fw-bold" style="font-size: 0.85rem;">Lihat Semua Lahan</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="height: 100%; max-height: 440px; overflow-y: auto;">
                    <table class="table table-metrics align-middle m-0">
                        <thead>
                            <tr>
                                <th>Nama Lahan</th>
                                <th>Luas (Ha)</th>
                                <th>Jumlah Tanaman</th>
                                <th>Usia Tanaman</th>
                                <th>Estimasi Panen</th>
                                <th>Status Kesehatan</th>
                                <th>Inspeksi Terakhir</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataLahan as $index => $lahan)
                                @php

                                    // Trend produktivitas
                                    $trendClass = 'up';
                                    $trendArrow = 'fa-arrow-trend-up';
                                    if ($lahan->produktivitas < 60) {
                                        $trendClass = 'down';
                                        $trendArrow = 'fa-arrow-trend-down';
                                    } elseif ($lahan->produktivitas < 85) {
                                        $trendClass = 'stable';
                                        $trendArrow = 'fa-arrow-right';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="lahan-identitas">
                                            <div class="lahan-blok-name">{{ $lahan->nama_lahan }}</div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($lahan->luas_hektar, 1, ',', '.') }}</td>
                                    <td>{{ number_format($lahan->jumlah_tanaman, 0, ',', '.') }} Pohon</td>
                                    <td>{{ $lahan->usia_tanaman }} Bulan</td>
                                    <td class="text-muted">{{ $lahan->estimasi_panen ? \Carbon\Carbon::parse($lahan->estimasi_panen)->locale('id')->isoFormat('DD MMM YYYY') : '-' }}</td>
                                    <td>
                                        <span class="badge-health {{ strtolower(str_replace(' ', '_', $lahan->status_kesehatan)) }}">
                                            <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> {{ $lahan->status_kesehatan }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $lahan->inspeksi_terakhir }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('area-kebun.edit', $lahan->id_area) }}" class="btn btn-sm btn-outline-primary rounded-3" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('area-kebun.destroy', $lahan->id_area) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lahan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-map-location-dot mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                        <p class="m-0">Belum ada data area lahan terdaftar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sektor Land Use Distribution list (progress bars) -->
    <div class="col-lg-4 mb-4">
        <div class="use-card">
            <div>
                <h5 class="chart-title mb-4">Distribusi Penggunaan Lahan</h5>
                
                @php
                    $percentKopi = $totalArea > 0 ? round(($totalKopiArea / $totalArea) * 100) : 0;
                    $percentLada = $totalArea > 0 ? round(($totalLadaArea / $totalArea) * 100) : 0;
                    $percentSawit = $totalArea > 0 ? round(($totalSawitArea / $totalArea) * 100) : 0;
                @endphp

                <!-- Kopi Area Hectares progress bar -->
                <div class="use-item kopi">
                    <div class="use-info">
                        <span class="crop-label">Kopi (Arabica & Robusta)</span>
                        <span>{{ number_format($totalKopiArea, 2, ',', '.') }} Ha</span>
                    </div>
                    <div class="use-progress">
                        <div class="use-progress-fill" style="width: {{ $percentKopi }}%"></div>
                    </div>
                </div>

                <!-- Lada Hectares progress bar -->
                <div class="use-item lada">
                    <div class="use-info">
                        <span class="crop-label">Lada (Pepper)</span>
                        <span>{{ number_format($totalLadaArea, 2, ',', '.') }} Ha</span>
                    </div>
                    <div class="use-progress">
                        <div class="use-progress-fill" style="width: {{ $percentLada }}%"></div>
                    </div>
                </div>

                <!-- Sawit Hectares progress bar -->
                <div class="use-item sawit">
                    <div class="use-info">
                        <span class="crop-label">Kelapa Sawit</span>
                        <span>{{ number_format($totalSawitArea, 2, ',', '.') }} Ha</span>
                    </div>
                    <div class="use-progress">
                        <div class="use-progress-fill" style="width: {{ $percentSawit }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Total acreage summary box -->
            <div class="total-area-box">
                <div class="total-area-label">Total Area</div>
                <div class="total-area-value">{{ number_format($totalArea, 2, ',', '.') }} Ha</div>
            </div>
        </div>
    </div>
</div>



@endsection
