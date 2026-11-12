@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* Premium Glowing Stats Card */
    .stat-card {
        border-radius: 20px;
        color: #ffffff;
        padding: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        border: none;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-card-owner {
        background: linear-gradient(135deg, #135d37 0%, #198754 100%);
    }

    .stat-card-harvest {
        background: linear-gradient(135deg, #0e5e75 0%, #0891b2 100%);
    }

    .stat-card-operational {
        background: linear-gradient(135deg, #9a3412 0%, #ea580c 100%);
    }

    .stat-card .icon-bg {
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-size: 8rem;
        color: rgba(255, 255, 255, 0.12);
        pointer-events: none;
    }

    .stat-value {
        font-size: 2.3rem;
        font-weight: 800;
        margin-top: 5px;
        font-family: 'Outfit', sans-serif;
    }

    .stat-label {
        font-size: 0.88rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        font-weight: 600;
    }

    /* Crop breakdown cards */
    .crop-widget {
        border-left: 5px solid transparent;
        padding: 15px 20px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.01);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        transition: all 0.2s;
    }

    .crop-widget:hover {
        transform: scale(1.02);
    }

    .crop-widget.kopi {
        border-left-color: #78350f;
    }
    .crop-widget.sawit {
        border-left-color: #eab308;
    }
    .crop-widget.lada {
        border-left-color: #10b981;
    }

    .crop-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .crop-icon-box.kopi {
        background-color: #fef3c7;
        color: #78350f;
    }
    .crop-icon-box.sawit {
        background-color: #fef9c3;
        color: #ca8a04;
    }
    .crop-icon-box.lada {
        background-color: #d1fae5;
        color: #059669;
    }

    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Selamat Datang di Perkebunan Nyapah Banyu</h2>
        <p class="text-muted m-0">Ringkasan pengelolaan komoditas Kopi, Sawit, & Lada hari ini (Lampung Utara)</p>
    </div>
    <div class="col-auto">
        <span class="badge bg-success p-2 rounded-3 text-uppercase">
            <i class="fa-solid fa-calendar-days me-1"></i> {{ date('d F Y') }}
        </span>
    </div>
</div>

<!-- STOK ALERT NOTIFIKASI PUPUK KRITIS -->
@if($notifikasiPupuk)
<div class="alert alert-danger border-0 rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center gap-3">
    <i class="fa-solid fa-triangle-exclamation text-danger" style="font-size: 2.2rem;"></i>
    <div>
        <h5 class="fw-bold m-0 text-danger">Peringatan Kritis Gudang!</h5>
        <p class="m-0 text-dark" style="font-size: 0.95rem;">{{ $notifikasiPupuk }}</p>
    </div>
</div>
@endif

<!-- CARDS STATISTIK UTAMA -->
<div class="row mb-3">
    <!-- Luas Lahan -->
    <div class="col-md-4 mb-4">
        <div class="stat-card stat-card-owner">
            <div class="stat-label">Total Lahan Kebun</div>
            <div class="stat-value">{{ number_format($totalLuasLahan, 2, ',', '.') }} <span style="font-size: 1.2rem;">Hektar</span></div>
            <div class="mt-2 text-white-50" style="font-size: 0.85rem;"><i class="fa-solid fa-map"></i> Terbagi kopi, sawit, lada</div>
            <i class="fa-solid fa-map-location-dot icon-bg"></i>
        </div>
    </div>
    
    <!-- Total Hasil Panen -->
    <div class="col-md-4 mb-4">
        <div class="stat-card stat-card-harvest">
            <div class="stat-label">Total Panen Bersih</div>
            <div class="stat-value">{{ number_format($totalPanenNetto, 2, ',', '.') }} <span style="font-size: 1.2rem;">KG</span></div>
            <div class="mt-2 text-white-50" style="font-size: 0.85rem;"><i class="fa-solid fa-scale-balanced"></i> Hasil sortasi (Netto)</div>
            <i class="fa-solid fa-wheat-awn icon-bg"></i>
        </div>
    </div>

    <!-- Total Pengeluaran -->
    <div class="col-md-4 mb-4">
        <div class="stat-card stat-card-operational">
            <div class="stat-label">Total Biaya Pengeluaran</div>
            <div class="stat-value"><span style="font-size: 1.4rem;">Rp</span>{{ number_format($totalPengeluaran, 2, ',', '.') }}</div>
            <div class="mt-2 text-white-50" style="font-size: 0.85rem;"><i class="fa-solid fa-coins"></i> Operasional + Gaji pekerja</div>
            <i class="fa-solid fa-wallet icon-bg"></i>
        </div>
    </div>
</div>

<!-- GRAFIK INTERAKTIF -->
<div class="row mb-4">
    <!-- Grafik Pendapatan Panen -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-chart-column me-2"></i> Grafik Pendapatan Hasil Panen ({{ date('Y') }})</span>
                <span class="badge bg-success">Bar Chart</span>
            </div>
            <div class="card-body">
                @if(count($nilaiPanen) > 0)
                    <div class="chart-container">
                        <canvas id="grafikPanen"></canvas>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center text-muted" style="height: 320px;">
                        <span><i class="fa-solid fa-inbox me-1"></i> Belum ada data transaksi panen di tahun ini.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Grafik Biaya Pengeluaran -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-chart-line me-2"></i> Grafik Pengeluaran Bulanan ({{ date('Y') }})</span>
                <span class="badge bg-danger">Line Chart</span>
            </div>
            <div class="card-body">
                @if(count($nilaiPengeluaran) > 0)
                    <div class="chart-container">
                        <canvas id="grafikPengeluaran"></canvas>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center text-muted" style="height: 320px;">
                        <span><i class="fa-solid fa-inbox me-1"></i> Belum ada data pengeluaran operasional di tahun ini.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- BREAKDOWN SEBARAN KOMODITAS (KOPI, SAWIT, LADA) -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-leaf me-1"></i> Komoditas Kopi
            </div>
            <div class="card-body">
                <div class="crop-widget kopi">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Luas Lahan</h6>
                        <small class="text-muted">{{ number_format(isset($statistikLahan['kopi']) ? $statistikLahan['kopi']->total_luas : 0, 2, ',', '.') }} Hektar</small>
                    </div>
                    <div class="crop-icon-box kopi">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                </div>

                <div class="crop-widget kopi">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Hasil Panen</h6>
                        <small class="text-muted">{{ number_format(isset($statistikPanen['kopi']) ? $statistikPanen['kopi']->total_netto : 0, 2, ',', '.') }} KG</small>
                    </div>
                    <div class="crop-icon-box kopi">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                </div>

                <div class="crop-widget kopi">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Omset Panen</h6>
                        <small class="text-muted">Rp {{ number_format(isset($statistikPanen['kopi']) ? $statistikPanen['kopi']->total_omset : 0, 2, ',', '.') }}</small>
                    </div>
                    <div class="crop-icon-box kopi">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-leaf me-1"></i> Komoditas Sawit
            </div>
            <div class="card-body">
                <div class="crop-widget sawit">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Luas Lahan</h6>
                        <small class="text-muted">{{ number_format(isset($statistikLahan['sawit']) ? $statistikLahan['sawit']->total_luas : 0, 2, ',', '.') }} Hektar</small>
                    </div>
                    <div class="crop-icon-box sawit">
                        <i class="fa-solid fa-tree"></i>
                    </div>
                </div>

                <div class="crop-widget sawit">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Hasil Panen</h6>
                        <small class="text-muted">{{ number_format(isset($statistikPanen['sawit']) ? $statistikPanen['sawit']->total_netto : 0, 2, ',', '.') }} KG</small>
                    </div>
                    <div class="crop-icon-box sawit">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                </div>

                <div class="crop-widget sawit">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Omset Panen</h6>
                        <small class="text-muted">Rp {{ number_format(isset($statistikPanen['sawit']) ? $statistikPanen['sawit']->total_omset : 0, 2, ',', '.') }}</small>
                    </div>
                    <div class="crop-icon-box sawit">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-leaf me-1"></i> Komoditas Lada
            </div>
            <div class="card-body">
                <div class="crop-widget lada">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Luas Lahan</h6>
                        <small class="text-muted">{{ number_format(isset($statistikLahan['lada']) ? $statistikLahan['lada']->total_luas : 0, 2, ',', '.') }} Hektar</small>
                    </div>
                    <div class="crop-icon-box lada">
                        <i class="fa-solid fa-pepper-hot"></i>
                    </div>
                </div>

                <div class="crop-widget lada">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Hasil Panen</h6>
                        <small class="text-muted">{{ number_format(isset($statistikPanen['lada']) ? $statistikPanen['lada']->total_netto : 0, 2, ',', '.') }} KG</small>
                    </div>
                    <div class="crop-icon-box lada">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                </div>

                <div class="crop-widget lada">
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Omset Panen</h6>
                        <small class="text-muted">Rp {{ number_format(isset($statistikPanen['lada']) ? $statistikPanen['lada']->total_omset : 0, 2, ',', '.') }}</small>
                    </div>
                    <div class="crop-icon-box lada">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // A. GRAFIK PENDAPATAN PANEN (Chart.js)
        @if(count($nilaiPanen) > 0)
            const ctxPanen = document.getElementById('grafikPanen').getContext('2d');
            new Chart(ctxPanen, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labelPanen) !!},
                    datasets: [{
                        label: 'Pendapatan Panen (Rp)',
                        data: {!! json_encode($nilaiPanen) !!},
                        backgroundColor: 'rgba(25, 135, 84, 0.85)',
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        @endif

        // B. GRAFIK PENGELUARAN BULANAN (Chart.js)
        @if(count($nilaiPengeluaran) > 0)
            const ctxPengeluaran = document.getElementById('grafikPengeluaran').getContext('2d');
            new Chart(ctxPengeluaran, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labelPengeluaran) !!},
                    datasets: [{
                        label: 'Pengeluaran Bulanan (Rp)',
                        data: {!! json_encode($nilaiPengeluaran) !!},
                        borderColor: '#ea580c',
                        backgroundColor: 'rgba(234, 88, 12, 0.08)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#ea580c',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        @endif
    });
</script>
@endsection
