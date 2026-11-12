@extends('layouts.app')

@section('title', 'Dashboard Lada')

@section('styles')
<style>
    /* Theme overrides for Lada theme */
    :root {
        --primary-green: #065f46; /* Emerald/Mint dark */
        --emerald-green: #10b981; /* Emerald light accent */
        --light-green: #f0fdf4; /* Light green tint */
        --mint-green: #d1fae5; /* Soft mint */
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

    /* Lada Dashboard Card Styles */
    .stat-card-lada {
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(6, 95, 70, 0.03);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card-lada::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #065f46);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card-lada:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(6, 95, 70, 0.08);
    }

    .stat-card-lada:hover::before {
        opacity: 1;
    }

    .stat-card-lada .metric-val {
        font-size: 2.20rem;
        font-weight: 800;
        color: #0f172a;
        font-family: 'Outfit', sans-serif;
        margin-top: 8px;
    }

    .stat-card-lada .metric-lbl {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .stat-card-lada .trend-lbl {
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .trend-up { color: #059669; }
    .trend-stable { color: #0284c7; }

    .card-lada-dark {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        color: #ffffff;
        border: none;
    }

    .card-lada-dark::before {
        display: none;
    }

    .card-lada-dark .metric-val,
    .card-lada-dark .metric-lbl {
        color: #ffffff;
    }

    .card-lada-dark .metric-lbl {
        color: #a7f3d0;
    }

    .card-lada-dark .trend-lbl {
        color: #d1fae5;
    }

    .icon-wrapper-stat {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        background-color: #f0fdf4;
        color: #059669;
        transition: all 0.3s;
    }

    .stat-card-lada:hover .icon-wrapper-stat {
        background-color: #065f46;
        color: #ffffff;
    }

    .card-lada-dark .icon-wrapper-stat {
        background-color: rgba(255, 255, 255, 0.1);
        color: #d1fae5;
    }

    .card-lada-dark:hover .icon-wrapper-stat {
        background-color: #10b981;
        color: #065f46;
    }

    /* Block status box */
    .block-row-lada {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .block-row-lada:hover {
        background-color: #f0fdf4;
        transform: translateX(4px);
        border-color: #a7f3d0;
    }

    .block-code-avatar {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background-color: #d1fae5;
        color: #065f46;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
    }

    .block-status-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bg-optimal { background-color: #d1fae5; color: #065f46; }
    .bg-perhatian { background-color: #fee2e2; color: #991b1b; }
    .bg-growing { background-color: #dbeafe; color: #1e40af; }
    .bg-replanting { background-color: #fee2e2; color: #991b1b; }

    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #065f46;
        font-family: 'Outfit', sans-serif;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Dashboard Lada</h2>
        <p class="text-muted m-0">Selamat pagi, {{ Auth::user()->name }}. Berikut ringkasan kebun lada hari ini.</p>
    </div>
    <div class="col-auto">
        <span class="badge p-2 rounded-3 text-uppercase" style="background-color: #065f46; color: #ffffff;">
            <i class="fa-solid fa-pepper-hot me-1"></i> Pengurus Lada
        </span>
    </div>
</div>

<!-- Stats Card Row -->
<div class="row mb-4">
    <!-- Total Produksi -->
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="stat-card-lada">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Total Produksi Lada</span>
                    <div class="metric-val">{{ number_format($totalLadaYield, 0, ',', '.') }} KG</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-weight-hanging"></i>
                </div>
            </div>
            <div class="trend-lbl trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span><strong>+19%</strong> vs bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Luas Lahan Aktif -->
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="stat-card-lada">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Luas Lahan Aktif</span>
                    <div class="metric-val">{{ number_format($totalLadaArea, 1, ',', '.') }} HA</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
            </div>
            <div class="trend-lbl trend-stable">
                <i class="fa-solid fa-circle-check"></i>
                <span>Semua area termonitor</span>
            </div>
        </div>
    </div>

    <!-- Total Pekerja Lada -->
    <div class="col-md-4">
        <div class="stat-card-lada card-lada-dark">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Total Pekerja Lada</span>
                    <div class="metric-val">{{ $totalLadaWorkers }} Jiwa</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="trend-lbl">
                <i class="fa-solid fa-people-carry-box"></i>
                <span>SDM aktif di lapangan</span>
            </div>
        </div>
    </div>
</div>

<!-- Yield Trends & Block Status Row -->
<div class="row mb-4">
    <!-- Production Trend Chart -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                <span class="section-title">
                    <i class="fa-solid fa-chart-line me-2"></i>Tren Hasil Panen Lada
                </span>
                <span class="text-muted" style="font-size: 0.8rem;">Data historis 6 bulan terakhir</span>
            </div>
            <div class="card-body">
                <div style="height: 320px; position: relative;">
                    <canvas id="ladaProductionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Kesehatan Blok -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <span class="section-title">
                    <i class="fa-solid fa-leaf me-2"></i>Status Kesehatan Blok
                </span>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    @foreach($ladaBlocks as $block)
                        <a href="{{ route('area-kebun.index') }}" class="block-row-lada">
                            <div class="d-flex align-items-center gap-3">
                                <div class="block-code-avatar">{{ $block['code'] }}</div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $block['name'] }}</div>
                                    <small class="text-muted">{{ number_format($block['tree_count']) }} Tiang • {{ $block['hectares'] }} HA</small>
                                </div>
                            </div>
                            @php
                                $statusLabel = $block['status'];
                                if (strtolower($statusLabel) == 'replanting') {
                                    $statusLabel = 'Penanaman Kembali';
                                }
                            @endphp
                            <span class="block-status-badge {{ $block['status'] == 'Optimal' ? 'bg-optimal' : 'bg-perhatian' }}">
                                {{ $statusLabel }}
                            </span>
                        </a>
                    @endforeach
                </div>
                
                <div class="text-center mt-3 pt-3 border-top">
                    <a href="{{ route('area-kebun.index') }}" class="text-decoration-none fw-bold" style="color: #065f46; font-size: 0.9rem;">
                        Lihat Semua Blok Lahan <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="section-title">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Aktivitas Terbaru
                </span>
                <div>
                    <a href="{{ route('operasional.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.8rem; border-color: #d1fae5; color: #065f46;">
                        Semua Log
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle m-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Aktivitas</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Waktu</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ladaActivities as $act)
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">{{ $act['title'] }}</td>
                                    <td>
                                        <span class="badge bg-light text-success border border-success-subtle px-2 py-1">
                                            {{ $act['category'] }}
                                        </span>
                                    </td>
                                    <td>{{ $act['location'] }}</td>
                                    <td class="text-muted">{{ $act['time'] }}</td>
                                    <td class="text-end pe-4">
                                        <span class="badge {{ $act['status'] == 'SELESAI' ? 'bg-success' : 'bg-primary' }} px-3 py-1 text-uppercase" style="font-size: 0.72rem; font-weight: 700;">
                                            {{ $act['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button for Quick Panen -->
<button class="fab-quick-add" onclick="window.location.href='{{ route('panen.index') }}'" title="Catat Panen Baru" style="position: fixed; bottom: 30px; right: 30px; width: 56px; height: 56px; border-radius: 50%; background-color: #065f46; color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(6, 95, 70, 0.3); cursor: pointer; border: none; z-index: 1050; transition: all 0.2s;">
    <i class="fa-solid fa-plus fs-4"></i>
</button>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const ctx = document.getElementById('ladaProductionChart').getContext('2d');
        
        // Gradient fill for line chart area
        const ladaGradient = ctx.createLinearGradient(0, 0, 0, 300);
        ladaGradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        ladaGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($weeklyProduction)) !!},
                datasets: [{
                    label: 'Produksi Lada (KG)',
                    data: {!! json_encode(array_values($weeklyProduction)) !!},
                    borderColor: '#10b981',
                    borderWidth: 3,
                    backgroundColor: ladaGradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Inter', weight: '600' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return value + ' kg'; },
                            font: { family: 'Inter' }
                        },
                        grid: {
                            color: '#f8fafc'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
