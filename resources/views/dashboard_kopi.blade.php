@extends('layouts.app')

@section('title', 'Dashboard Kopi')

@section('styles')
<style>
    /* Theme overrides for Coffee theme */
    :root {
        --primary-green: #3d2314; /* Deep coffee espresso */
        --emerald-green: #5c3a21; /* Warm coffee brown */
        --light-green: #faf7f2; /* Creamy beige */
        --mint-green: #e6ccb2; /* Light coffee bean */
        --accent-green: #c9a054; /* Gold accent */
    }

    /* Override layout elements for coffee branding */
    .sidebar-link.active {
        color: #3d2314 !important;
        background-color: #e6ccb2 !important;
        border-left: 4px solid #c9a054 !important;
    }
    .btn-add-land {
        background-color: #3d2314 !important;
    }
    .btn-add-land:hover {
        background-color: #5c3a21 !important;
    }
    .text-success {
        color: #5c3a21 !important;
    }

    /* Premium Coffee Dashboard Card Styles */
    .stat-card-kopi {
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(92, 58, 33, 0.04);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card-kopi::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #5c3a21, #c9a054);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card-kopi:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(92, 58, 33, 0.08);
    }

    .stat-card-kopi:hover::before {
        opacity: 1;
    }

    .stat-card-kopi .metric-val {
        font-size: 2.2rem;
        font-weight: 800;
        color: #2b170c;
        font-family: 'Outfit', sans-serif;
        margin-top: 8px;
    }

    .stat-card-kopi .metric-lbl {
        font-size: 0.78rem;
        font-weight: 700;
        color: #8a7a71;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .stat-card-kopi .trend-lbl {
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .trend-up { color: #2d6a4f; }
    .trend-down { color: #ae2012; }
    .trend-stable { color: #005f73; }

    .card-kopi-dark {
        background: linear-gradient(135deg, #3d2314 0%, #2b170c 100%);
        color: #ffffff;
        border: none;
    }

    .card-kopi-dark::before {
        display: none;
    }

    .card-kopi-dark .metric-val,
    .card-kopi-dark .metric-lbl {
        color: #ffffff;
    }

    .card-kopi-dark .metric-lbl {
        color: #d4a373;
    }

    .card-kopi-dark .trend-lbl {
        color: #e6ccb2;
    }

    .icon-wrapper-stat {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        background-color: #faf7f2;
        color: #5c3a21;
        transition: all 0.3s;
    }

    .stat-card-kopi:hover .icon-wrapper-stat {
        background-color: #5c3a21;
        color: #ffffff;
    }

    .card-kopi-dark .icon-wrapper-stat {
        background-color: rgba(255, 255, 255, 0.1);
        color: #e6ccb2;
    }

    .card-kopi-dark:hover .icon-wrapper-stat {
        background-color: #c9a054;
        color: #3d2314;
    }

    /* Coffee Activity Feed */
    .activity-feed-kopi {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .activity-item-kopi {
        padding: 16px;
        border-left: 3px solid #e6ccb2;
        margin-bottom: 16px;
        background-color: #faf7f2;
        border-radius: 0 12px 12px 0;
        transition: all 0.2s;
    }

    .activity-item-kopi:hover {
        border-left-color: #c9a054;
        transform: translateX(4px);
        background-color: #f5efe6;
    }

    .activity-time-kopi {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #a08a7c;
        letter-spacing: 0.5px;
    }

    .activity-title-kopi {
        font-weight: 700;
        font-size: 0.92rem;
        color: #3d2314;
        margin-top: 4px;
        margin-bottom: 2px;
    }

    .activity-desc-kopi {
        font-size: 0.85rem;
        color: #7f6e62;
    }

    /* Coffee Block Row */
    .block-row-kopi {
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

    .block-row-kopi:hover {
        background-color: #faf7f2;
        transform: translateX(4px);
        border-color: #e6ccb2;
    }

    .block-code-avatar {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background-color: #e6ccb2;
        color: #3d2314;
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

    .bg-healthy { background-color: #d1fae5; color: #065f46; }
    .bg-growing { background-color: #dbeafe; color: #1e40af; }
    .bg-harvesting { background-color: #fef3c7; color: #92400e; }
    .bg-maintenance { background-color: #f3e8ff; color: #6b21a8; }
    .bg-replanting { background-color: #fee2e2; color: #991b1b; }

    /* Button and headers */
    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #3d2314;
        font-family: 'Outfit', sans-serif;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Dashboard Kopi</h2>
        <p class="text-muted m-0">Ringkasan operasional, log panen, dan produktivitas komoditas Kopi Arabika & Robusta.</p>
    </div>
    <div class="col-auto">
        <span class="badge p-2 rounded-3 text-uppercase" style="background-color: #3d2314; color: #ffffff;">
            <i class="fa-solid fa-mug-hot me-1"></i> Pengurus Kopi
        </span>
    </div>
</div>

<!-- Stats Card Row -->
<div class="row mb-4">
    <!-- Total Panen -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="stat-card-kopi">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Total Produksi</span>
                    <div class="metric-val">{{ number_format($totalCoffeeYield, 0, ',', '.') }} kg</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-weight-hanging"></i>
                </div>
            </div>
            <div class="trend-lbl trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span><strong>+15.2%</strong> vs bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Blok Aktif -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="stat-card-kopi">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Blok Aktif</span>
                    <div class="metric-val">{{ $activeCoffeeBlocks }} Blok</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-border-all"></i>
                </div>
            </div>
            <div class="trend-lbl trend-stable">
                <i class="fa-solid fa-circle-check"></i>
                <span>Semua blok termonitor</span>
            </div>
        </div>
    </div>

    <!-- Total Area -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="stat-card-kopi">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Total Area</span>
                    <div class="metric-val">{{ number_format($totalKopiArea, 1, ',', '.') }} Ha</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
            </div>
            <div class="trend-lbl trend-up">
                <i class="fa-solid fa-seedling"></i>
                <span>Arabika & Robusta</span>
            </div>
        </div>
    </div>

    <!-- Biaya Pemeliharaan -->
    <div class="col-md-3">
        <div class="stat-card-kopi card-kopi-dark">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Biaya Pemeliharaan</span>
                    <div class="metric-val">Rp {{ number_format($maintenanceCosts, 0, ',', '.') }}</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <div class="trend-lbl">
                <i class="fa-solid fa-calculator"></i>
                <span>Bulan berjalan</span>
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
                    <i class="fa-solid fa-chart-line me-2"></i>Produksi Mingguan (7 Hari Terakhir)
                </span>
                <span class="text-muted" style="font-size: 0.8rem;">Hasil panen kopi harian (kg)</span>
            </div>
            <div class="card-body">
                <div style="height: 320px; position: relative;">
                    <canvas id="coffeeProductionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Sektor / Status Lahan -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <span class="section-title">
                    <i class="fa-solid fa-leaf me-2"></i>Blok Lahan Kopi
                </span>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    @foreach(array_slice($coffeeBlocks, 0, 4) as $block)
                        <a href="{{ route('area-kebun.index') }}" class="block-row-kopi">
                            <div class="d-flex align-items-center gap-3">
                                <div class="block-code-avatar">{{ $block['code'] }}</div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $block['name'] }}</div>
                                    <small class="text-muted">{{ number_format($block['tree_count']) }} Pohon • {{ $block['soil'] }}</small>
                                </div>
                            </div>
                            @php
                                $statusClass = 'healthy';
                                $statusLabel = $block['status'];
                                $statusLower = strtolower($block['status']);
                                if ($statusLower === 'healthy') {
                                    $statusClass = 'healthy';
                                    $statusLabel = 'Sehat';
                                } elseif ($statusLower === 'growing') {
                                    $statusClass = 'growing';
                                    $statusLabel = 'Tumbuh';
                                } elseif ($statusLower === 'harvesting') {
                                    $statusClass = 'harvesting';
                                    $statusLabel = 'Panen';
                                } elseif ($statusLower === 'maintenance') {
                                    $statusClass = 'maintenance';
                                    $statusLabel = 'Pemeliharaan';
                                } elseif ($statusLower === 'replanting') {
                                    $statusClass = 'replanting';
                                    $statusLabel = 'Penanaman Kembali';
                                }
                            @endphp
                            <span class="block-status-badge bg-{{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </a>
                    @endforeach
                </div>
                
                <div class="text-center mt-3 pt-3 border-top">
                    <a href="{{ route('area-kebun.index') }}" class="text-decoration-none fw-bold" style="color: #5c3a21; font-size: 0.9rem;">
                        Lihat Semua Blok Lahan <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Live Stream Aktivitas Kebun Kopi -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="section-title">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Aktivitas Terbaru Kebun Kopi
                </span>
                <a href="{{ route('operasional.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.8rem; border-color: #e6ccb2; color: #3d2314;">
                    Lihat Semua Log
                </a>
            </div>
            <div class="card-body">
                <ul class="activity-feed-kopi">
                    @foreach($activities as $act)
                        <li class="activity-item-kopi">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="activity-time-kopi"><i class="fa-solid fa-clock me-1"></i> {{ $act['time'] }}</span>
                                <span class="fw-bold fs-7 {{ $act['class'] }} text-uppercase">Info</span>
                            </div>
                            <div class="activity-title-kopi">{{ $act['title'] }}</div>
                            <div class="activity-desc-kopi">{{ $act['desc'] }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const ctx = document.getElementById('coffeeProductionChart').getContext('2d');
        
        // Gradient for chart bars
        const coffeeGradient = ctx.createLinearGradient(0, 0, 0, 300);
        coffeeGradient.addColorStop(0, '#5c3a21');
        coffeeGradient.addColorStop(1, '#e6ccb2');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($weeklyProduction)) !!},
                datasets: [{
                    label: 'Produksi Kopi (Kg)',
                    data: {!! json_encode(array_values($weeklyProduction)) !!},
                    backgroundColor: function(context) {
                        const index = context.dataIndex;
                        const count = context.chart.data.datasets[0].data.length;
                        // Highlight the current peak bar (e.g. FRI) in gold accent
                        return index === 4 ? '#c9a054' : coffeeGradient;
                    },
                    borderRadius: 8,
                    barThickness: 32
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
