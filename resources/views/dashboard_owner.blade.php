@extends('layouts.app')

@section('title', 'Monitoring Kinerja Pengurus')

@section('styles')
<style>
    /* Custom Styling for Premium Owner Dashboard */
    .dashboard-header {
        margin-bottom: 25px;
    }

    .dashboard-title {
        font-weight: 800;
        color: var(--primary-green);
        margin: 0;
    }

    .dashboard-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    /* Manager Cards */
    .manager-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.01), 0 2px 4px -1px rgba(0,0,0,0.01);
        transition: all 0.25s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .manager-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }

    .manager-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .manager-avatar-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .manager-avatar-box.kopi {
        background-color: #fef3c7;
        color: #b45309;
    }
    
    .manager-avatar-box.lada {
        background-color: #d1fae5;
        color: #065f46;
    }

    .manager-avatar-box.sawit {
        background-color: #e0f2fe;
        color: #0369a1;
    }

    .manager-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 30px;
    }

    .manager-badge.kopi {
        background-color: #fffbeb;
        color: #b45309;
        border: 1px solid #fef3c7;
    }
    
    .manager-badge.lada {
        background-color: #f0fdf4;
        color: #065f46;
        border: 1px solid #d1fae5;
    }

    .manager-badge.sawit {
        background-color: #f0f9ff;
        color: #0369a1;
        border: 1px solid #e0f2fe;
    }

    .manager-info h4 {
        font-weight: 800;
        color: var(--dark-charcoal);
        margin: 0;
        font-size: 1.2rem;
    }

    .manager-info p {
        color: #64748b;
        font-size: 0.85rem;
        margin: 2px 0 15px 0;
    }

    .manager-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .manager-stat-item {
        line-height: 1.3;
    }

    .manager-stat-label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
    }

    .manager-stat-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dark-charcoal);
    }

    .manager-progress-bar {
        height: 6px;
        background-color: #f1f5f9;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 18px;
    }

    .manager-progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.8s ease-in-out;
    }

    .manager-progress-fill.kopi { background-color: #d97706; }
    .manager-progress-fill.lada { background-color: #10b981; }
    .manager-progress-fill.sawit { background-color: #0284c7; }

    .manager-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        color: #64748b;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .manager-footer i.arrow {
        color: var(--primary-green);
        transition: transform 0.2s;
    }

    .manager-card:hover .manager-footer i.arrow {
        transform: translateX(4px);
    }

    /* Integrated Yield Comparison Chart Container */
    .yield-chart-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-green);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-title i {
        color: #94a3b8;
        font-size: 0.95rem;
        cursor: pointer;
    }

    /* Alerts Panel */
    .alerts-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
    }

    .alerts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .alerts-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-green);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alerts-count-badge {
        background-color: #fef2f2;
        color: #ef4444;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        border: 1px solid #fee2e2;
    }

    .alert-item {
        display: flex;
        gap: 15px;
        padding: 16px;
        border-radius: 14px;
        margin-bottom: 12px;
        border: 1px solid transparent;
        transition: all 0.2s;
    }

    .alert-item:hover {
        transform: scale(1.01);
    }

    .alert-item.danger {
        background-color: #fff5f5;
        border-color: #fee2e2;
        border-left: 4px solid #ef4444;
    }

    .alert-item.warning {
        background-color: #fffbeb;
        border-color: #fef3c7;
        border-left: 4px solid #f59e0b;
    }

    .alert-item.secondary {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        border-left: 4px solid #64748b;
    }

    .alert-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .alert-item.danger .alert-icon-box {
        background-color: #fee2e2;
        color: #ef4444;
    }

    .alert-item.warning .alert-icon-box {
        background-color: #fef3c7;
        color: #d97706;
    }

    .alert-item.secondary .alert-icon-box {
        background-color: #e2e8f0;
        color: #64748b;
    }

    .alert-content h6 {
        font-weight: 700;
        color: var(--dark-charcoal);
        margin: 0 0 2px 0;
        font-size: 0.95rem;
    }

    .alert-content p {
        color: #64748b;
        font-size: 0.8rem;
        margin: 0 0 6px 0;
        line-height: 1.3;
    }

    .alert-time {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .alert-item.danger .alert-time { color: #ef4444; }
    .alert-item.warning .alert-time { color: #d97706; }
    .alert-item.secondary .alert-time { color: #64748b; }

    /* Live Activity Table */
    .activity-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-top: 25px;
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .activity-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-green);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .live-stream-badge {
        font-size: 0.75rem;
        font-weight: 700;
        color: #22c55e;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .live-stream-badge .blink-dot {
        width: 8px;
        height: 8px;
        background-color: #22c55e;
        border-radius: 50%;
        animation: blink 1.2s infinite;
    }

    @keyframes blink {
        0% { opacity: 0.2; }
        50% { opacity: 1; }
        100% { opacity: 0.2; }
    }

    .table-live-stream th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        font-weight: 700;
        border-bottom: 2px solid #f1f5f9;
        padding: 12px 16px;
    }

    .table-live-stream td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .table-live-stream tr:last-child td {
        border-bottom: none;
    }

    .badge-status-activity {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
    }

    .badge-status-activity.in_progress, .badge-status-activity.proses {
        background-color: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-status-activity.completed, .badge-status-activity.selesai {
        background-color: #f8fafc;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .badge-status-activity.scheduled, .badge-status-activity.terjadwal {
        background-color: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
</style>
@endsection

@section('content')
<div class="row align-items-center mb-4 dashboard-header">
    <div class="col">
        <h2 class="dashboard-title">Monitoring Kinerja Pengurus</h2>
        <p class="dashboard-subtitle">Pengawasan real-time operasional komoditas dan kinerja pengurus perkebunan.</p>
    </div>
    <div class="col-auto d-flex gap-3">
        <!-- Date Selector Dropdown -->
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle bg-white border-light-subtle rounded-3 shadow-sm fw-bold text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-calendar-days me-1 text-success"></i> {{ $filterLabel ?? 'Perbulan (Bulan Ini)' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                <li><a class="dropdown-item py-2 px-3 fw-medium {{ ($filter ?? 'bulan') == 'hari' ? 'active bg-success text-white' : '' }}" href="{{ route('dashboard', ['filter' => 'hari']) }}"><i class="fa-solid fa-calendar-day me-2"></i>Pertanggal (Hari Ini)</a></li>
                <li><a class="dropdown-item py-2 px-3 fw-medium {{ ($filter ?? 'bulan') == 'bulan' ? 'active bg-success text-white' : '' }}" href="{{ route('dashboard', ['filter' => 'bulan']) }}"><i class="fa-solid fa-calendar-week me-2"></i>Perbulan (Bulan Ini)</a></li>
                <li><a class="dropdown-item py-2 px-3 fw-medium {{ ($filter ?? 'bulan') == 'tahun' ? 'active bg-success text-white' : '' }}" href="{{ route('dashboard', ['filter' => 'tahun']) }}"><i class="fa-solid fa-calendar-days me-2"></i>Pertahun (Tahun Ini)</a></li>
            </ul>
        </div>
        <!-- Export Report Link -->
        <a href="{{ route('laporan.index') }}" class="btn btn-success">
            <i class="fa-solid fa-download me-1"></i> Ekspor Laporan
        </a>
    </div>
</div>

<!-- SUMMARY CARDS ROW -->
<div class="row mb-4">
    <!-- Panen Card -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="manager-card" style="border-top: 4px solid #10b981;">
            <div>
                <div class="manager-card-top">
                    <div class="manager-avatar-box lada">
                        <i class="fa-solid fa-wheat-awn"></i>
                    </div>
                    <span class="manager-badge lada">Panen</span>
                </div>
                <div class="manager-info">
                    <h4 class="mb-1">{{ number_format($summaryData['panen'], 2, ',', '.') }} Kg</h4>
                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">Total Netto Panen</p>
                </div>
            </div>
            <div class="manager-footer mt-3">
                <a href="{{ route('panen.index') }}" class="text-decoration-none d-flex align-items-center w-100 justify-content-between">
                    <span class="text-success fw-bold" style="font-size: 0.82rem;">Lihat Detail</span>
                    <i class="fa-solid fa-arrow-right arrow"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Pengiriman Card -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="manager-card" style="border-top: 4px solid #0284c7;">
            <div>
                <div class="manager-card-top">
                    <div class="manager-avatar-box sawit">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                    </div>
                    <span class="manager-badge sawit">Kirim</span>
                </div>
                <div class="manager-info">
                    <h4 class="mb-1">{{ number_format($summaryData['pengiriman'], 2, ',', '.') }} Kg</h4>
                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">Total Dikirim</p>
                </div>
            </div>
            <div class="manager-footer mt-3">
                <a href="{{ route('pengiriman.index') }}" class="text-decoration-none d-flex align-items-center w-100 justify-content-between">
                    <span class="text-primary fw-bold" style="font-size: 0.82rem;">Lihat Detail</span>
                    <i class="fa-solid fa-arrow-right arrow"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Operasional Card -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="manager-card" style="border-top: 4px solid #f59e0b;">
            <div>
                <div class="manager-card-top">
                    <div class="manager-avatar-box kopi">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <span class="manager-badge kopi">Operasional</span>
                </div>
                <div class="manager-info">
                    <h4 class="mb-1">{{ $summaryData['operasional'] }} Kegiatan</h4>
                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">Total Aktivitas Kebun</p>
                </div>
            </div>
            <div class="manager-footer mt-3">
                <a href="{{ route('operasional.index') }}" class="text-decoration-none d-flex align-items-center w-100 justify-content-between">
                    <span class="text-warning fw-bold" style="font-size: 0.82rem; color: #d97706 !important;">Lihat Detail</span>
                    <i class="fa-solid fa-arrow-right arrow"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- SDM Card -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="manager-card" style="border-top: 4px solid #64748b;">
            <div>
                <div class="manager-card-top">
                    <div class="manager-avatar-box" style="background-color: #f1f5f9; color: #475569;">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <span class="manager-badge" style="background-color: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">Upah</span>
                </div>
                <div class="manager-info">
                    <h4 class="mb-1">Rp {{ number_format($summaryData['sdm'], 0, ',', '.') }}</h4>
                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">Total Gaji Pekerja</p>
                </div>
            </div>
            <div class="manager-footer mt-3">
                <a href="{{ route('sdm.index') }}" class="text-decoration-none d-flex align-items-center w-100 justify-content-between">
                    <span class="text-secondary fw-bold" style="font-size: 0.82rem;">Lihat Detail</span>
                    <i class="fa-solid fa-arrow-right arrow"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS AND ALERTS ROW -->
<div class="row">
    <!-- Integrated Yield Comparison Chart -->
    <div class="col-lg-8 mb-4">
        <div class="yield-chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    Perbandingan Hasil Panen Terpadu
                    <i class="fa-solid fa-circle-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Perbandingan hasil panen netto Kopi, Lada, dan Kelapa Sawit (Ton)"></i>
                </h5>
            </div>
            <div style="height: 330px; position: relative;">
                <canvas id="yieldComparisonChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Critical Alerts -->
    <div class="col-lg-4 mb-4">
        <div class="alerts-card">
            <div class="alerts-header">
                <h5 class="alerts-title">
                    Status Pengiriman Pabrik
                </h5>
                <a href="{{ route('pengiriman.index') }}" class="text-success text-decoration-none fw-bold" style="font-size: 0.85rem;">Lihat Semua</a>
            </div>
            
            <div class="alerts-list">
                @forelse($recentPengiriman as $kirim)
                    <div class="alert-item {{ $kirim['type'] }}">
                        <div class="alert-icon-box">
                            <i class="fa-solid {{ $kirim['icon'] }}"></i>
                        </div>
                        <div class="alert-content">
                            <h6>{{ $kirim['title'] }}</h6>
                            <p>{{ $kirim['desc'] }}</p>
                            <span class="alert-time">{{ $kirim['time'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fa-solid fa-box-open fs-2 mb-2 text-light"></i>
                        <p class="mb-0" style="font-size: 0.85rem;">Belum ada pengiriman</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- LIVE MANAGER ACTIVITY TABLE -->
<div class="activity-card">
    <div class="activity-header">
        <h5 class="activity-title">Aktivitas Pengurus Real-time</h5>
        <span class="live-stream-badge">
            <span class="blink-dot"></span> Aliran Langsung
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-live-stream align-middle m-0">
            <thead>
                <tr>
                    <th>Pengurus</th>
                    <th>Aktivitas</th>
                    <th>Lokasi</th>
                    <th>Peralatan / Perlengkapan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($managerActivities as $act)
                    <tr>
                        <td class="fw-bold text-dark">{{ $act['manager'] }}</td>
                        <td>{{ $act['action'] }}</td>
                        <td class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ $act['location'] }}</td>
                        <td>{{ $act['resources'] }}</td>
                        <td>
                            @php
                                $statusClass = strtolower(str_replace(' ', '_', $act['status']));
                            @endphp
                            <span class="badge-status-activity {{ $statusClass }}">
                                {{ $act['status'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // INTEGRATED YIELD COMPARISON CHART (Chart.js)
        const ctx = document.getElementById('yieldComparisonChart').getContext('2d');
        
        // Multi-dataset chart matching mockup legends
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsBulan) !!},
                datasets: [
                    {
                        label: 'Kopi',
                        data: {!! json_encode($chartData['kopi']) !!},
                        borderColor: '#d97706',
                        backgroundColor: 'rgba(217, 119, 6, 0.03)',
                        borderWidth: 3,
                        pointBackgroundColor: '#d97706',
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Lada',
                        data: {!! json_encode($chartData['lada']) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.03)',
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Sawit',
                        data: {!! json_encode($chartData['sawit']) !!},
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.03)',
                        borderWidth: 3,
                        pointBackgroundColor: '#0284c7',
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                family: 'Inter',
                                size: 12,
                                weight: '600'
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' Ton';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
