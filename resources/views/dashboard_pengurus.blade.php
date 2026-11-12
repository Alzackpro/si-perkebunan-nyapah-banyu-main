@extends('layouts.app')

@section('title', 'Dashboard Sawit')

@section('styles')
<style>
    /* premium style custom dashboard sawit */
    .stat-card-sawit {
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card-sawit:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
    }

    .stat-card-sawit .metric-val {
        font-size: 2.2rem;
        font-weight: 800;
        color: #0f172a;
        font-family: 'Outfit', sans-serif;
    }

    .stat-card-sawit .metric-lbl {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .stat-card-sawit .trend-lbl {
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .trend-up { color: #166534; }
    .trend-down { color: #991b1b; }
    .trend-stable { color: #0369a1; }

    .card-sawit-green {
        background: linear-gradient(135deg, #0f5132 0%, #0a3d24 100%);
        color: #ffffff;
        border: none;
    }

    .card-sawit-green .metric-val,
    .card-sawit-green .metric-lbl {
        color: #ffffff;
    }

    .card-sawit-green .trend-lbl {
        color: #a7f3d0;
    }

    .icon-wrapper-stat {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        background-color: #f1f5f9;
        color: #0f5132;
    }

    .card-sawit-green .icon-wrapper-stat {
        background-color: rgba(255, 255, 255, 0.15);
        color: #ffffff;
    }

    /* Block status box */
    .block-status-item {
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

    .block-status-item:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
    }

    .block-status-code {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: #d1fae5;
        color: #065f46;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
    }

    .block-status-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
    }

    .bg-harvesting { background-color: #d1fae5; color: #065f46; }
    .bg-growing { background-color: #dbeafe; color: #1e40af; }
    .bg-replanting { background-color: #fee2e2; color: #991b1b; }
    .bg-resting { background-color: #f1f5f9; color: #475569; }

    /* Leaderboard custom styling */
    .table-leaderboard th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #f1f5f9;
        padding: 12px 16px;
    }

    .table-leaderboard td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .worker-avatar-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #bbf7d0;
    }

    /* Floating Action Button */
    .fab-quick-add {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background-color: #0a3d24;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 15px -3px rgba(10, 61, 36, 0.3), 0 4px 6px -2px rgba(10, 61, 36, 0.15);
        cursor: pointer;
        z-index: 1050;
        border: none;
        transition: all 0.2s;
    }

    .fab-quick-add:hover {
        background-color: #15803d;
        transform: scale(1.1) rotate(90deg);
        color: #ffffff;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Dashboard Sawit</h2>
        <p class="text-muted m-0">Ringkasan operasional dan performa kebun kelapa sawit Nyapah Banyu.</p>
    </div>
    <div class="col-auto">
        <span class="badge bg-success p-2 rounded-3 text-uppercase">
            <i class="fa-solid fa-calendar-days me-1"></i> {{ date('d F Y') }}
        </span>
    </div>
</div>

<!-- Stats Card Row -->
<div class="row mb-4">
    <!-- Total Panen -->
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="stat-card-sawit">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Total Panen</span>
                    <div class="metric-val mt-2">{{ number_format($totalPanenTon, 3, ',', '.') }} Ton</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-truck-moving"></i>
                </div>
            </div>
            <div class="trend-lbl trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span><strong>+12.4%</strong> dari bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Brondol Ratio -->
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="stat-card-sawit">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Brondol Ratio</span>
                    <div class="metric-val mt-2">{{ number_format($brondolRatio, 1, ',', '.') }}%</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-droplet"></i>
                </div>
            </div>
            <div class="trend-lbl trend-down">
                <i class="fa-solid fa-arrow-trend-down"></i>
                <span><strong>-2.1%</strong> Efisiensi kutip</span>
            </div>
        </div>
    </div>

    <!-- Efisiensi Panen -->
    <div class="col-md-4">
        <div class="stat-card-sawit card-sawit-green">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="metric-lbl">Efisiensi Panen</span>
                    <div class="metric-val mt-2">{{ $efisiensiPanen }}%</div>
                </div>
                <div class="icon-wrapper-stat">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
            </div>
            <div class="trend-lbl">
                <i class="fa-solid fa-circle-check"></i>
                <span>Di atas target (90%)</span>
            </div>
        </div>
    </div>
</div>

<!-- Yield Trends & Block Status Row -->
<div class="row mb-4">
    <!-- Yield Trends Chart -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                <span class="fw-bold text-success" style="font-size: 1.05rem;">
                    <i class="fa-solid fa-chart-line me-2"></i>Tren Hasil Panen
                </span>
                <span class="text-muted" style="font-size: 0.8rem;">Produksi mingguan kelapa sawit (Ton)</span>
            </div>
            <div class="card-body">
                <div style="height: 320px; position: relative;">
                    <canvas id="yieldTrendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Block Status -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <span class="fw-bold text-success" style="font-size: 1.05rem;">
                    <i class="fa-solid fa-border-all me-2"></i>Status Blok
                </span>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    @foreach($blocksStatus as $b)
                        <a href="{{ route('area-kebun.index') }}" class="block-status-item">
                            <div class="d-flex align-items-center gap-3">
                                <div class="block-status-code">A1</div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $b['code'] }}</div>
                                    <small class="text-muted">{{ number_format($b['tree_count']) }} Pohon</small>
                                </div>
                            </div>
                            <span class="block-status-badge {{ $b['status'] == 'Sedang Panen' ? 'bg-harvesting' : 'bg-resting' }}">
                                {{ $b['status'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
                
                <div class="text-center mt-3 pt-3 border-top">
                    <a href="{{ route('area-kebun.index') }}" class="text-success text-decoration-none fw-bold" style="font-size: 0.9rem;">
                        Lihat Semua Blok <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Worker Productivity Leaderboard -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-success" style="font-size: 1.05rem;">
                    <i class="fa-solid fa-award me-2"></i>Papan Peringkat Produktivitas Mandor
                </span>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-filter me-1"></i> Saring Tim
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-leaderboard align-middle m-0" style="min-width: 800px;">
                        <thead>
                            <tr>
                                <th>Mandor / Tim</th>
                                <th>Output (Kg)</th>
                                <th>Eff. %</th>
                                <th>Status</th>
                                <th class="text-center">Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaderboard as $worker)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=facearea&facepad=2&w=150&h=150&q=80" alt="{{ $worker['name'] }}" class="worker-avatar-badge">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $worker['name'] }}</div>
                                                <small class="text-muted">{{ $worker['team'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-dark">{{ $worker['output'] }} kg</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress" style="width: 100px; height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ $worker['efficiency'] }}%"></div>
                                            </div>
                                            <span class="fw-semibold">{{ $worker['efficiency'] }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $worker['class'] }} rounded-pill px-3 py-2" style="font-size: 0.72rem; font-weight: 700;">
                                            {{ $worker['status'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($worker['trend'] == 'up')
                                            <i class="fa-solid fa-arrow-trend-up text-success fs-5"></i>
                                        @elseif($worker['trend'] == 'down')
                                            <i class="fa-solid fa-arrow-trend-down text-danger fs-5"></i>
                                        @else
                                            <i class="fa-solid fa-arrows-left-right text-muted fs-5"></i>
                                        @endif
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
<button class="fab-quick-add" onclick="window.location.href='{{ route('panen.index') }}'" title="Catat Panen Baru">
    <i class="fa-solid fa-plus fs-4"></i>
</button>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const ctx = document.getElementById('yieldTrendsChart').getContext('2d');
        
        // Gradient for chart bars
        const greenGradient = ctx.createLinearGradient(0, 0, 0, 300);
        greenGradient.addColorStop(0, '#0f5132');
        greenGradient.addColorStop(1, '#a7f3d0');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($yieldTrends)) !!},
                datasets: [{
                    label: 'Produksi Kelapa Sawit (Ton)',
                    data: {!! json_encode(array_values($yieldTrends)) !!},
                    backgroundColor: function(context) {
                        const index = context.dataIndex;
                        const count = context.chart.data.datasets[0].data.length;
                        // Color the last current bar in dark green, others in lighter green
                        return index === count - 1 ? '#0a3d24' : '#cbd5e1';
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
                            callback: function(value) { return value + 'T'; },
                            font: { family: 'Inter' }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
