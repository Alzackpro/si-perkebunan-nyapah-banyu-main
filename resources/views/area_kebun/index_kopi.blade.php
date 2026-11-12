@extends('layouts.app')

@section('title', 'Area Kebun Kopi')

@section('styles')
<style>
    /* Use default green theme from app.blade.php */

    /* Coffee land specific styles using green variables */
    .coffee-banner {
        background: linear-gradient(135deg, var(--primary-green) 0%, var(--emerald-green) 100%);
        border-radius: 20px;
        padding: 40px;
        color: #ffffff;
        margin-bottom: 30px;
        position: relative;
        box-shadow: 0 4px 20px rgba(10, 61, 36, 0.15);
    }

    .stat-card-kopi-land {
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(10, 61, 36, 0.03);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s;
    }

    .stat-card-kopi-land:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(10, 61, 36, 0.06);
    }

    .stat-card-kopi-land.highlight {
        background-color: var(--primary-green);
        color: #ffffff;
        border: none;
    }

    .stat-card-kopi-land.highlight .metric-lbl {
        color: var(--mint-green);
    }

    .stat-card-kopi-land.highlight .metric-val {
        color: #ffffff;
    }

    .stat-card-kopi-land .metric-lbl {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .stat-card-kopi-land .metric-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--primary-green);
        margin-top: 4px;
        font-family: 'Outfit', sans-serif;
    }

    .stat-card-kopi-land.highlight .metric-val {
        color: #ffffff;
    }

    .stat-card-kopi-land .metric-badge {
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Filter tab styling */
    .filter-tab-kopi {
        padding: 8px 18px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #64748b;
        font-size: 0.88rem;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .filter-tab-kopi:hover {
        background-color: var(--light-green);
        color: var(--primary-green);
    }

    .filter-tab-kopi.active {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: #ffffff;
    }

    /* Coffee land table design */
    .table-coffee-land th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        background-color: var(--light-green);
    }

    .table-coffee-land td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }

    .block-code-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: var(--mint-green);
        color: var(--primary-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
    }

    .badge-status-coffee {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 30px;
        text-transform: uppercase;
    }

    .badge-status-coffee.healthy {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-status-coffee.growing {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-status-coffee.replanting {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .badge-varietas-coffee {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        background-color: var(--light-green);
        color: var(--emerald-green);
        border: 1px solid var(--mint-green);
    }

    /* Map and graphic details */
    .map-box-coffee {
        background-color: var(--light-green);
        border-radius: 12px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px dashed var(--mint-green);
        position: relative;
        overflow: hidden;
    }

    @media (min-width: 768px) {
        .table-responsive {
            overflow: visible !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Coffee Header Banner -->
<div class="coffee-banner">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h2 class="m-0 text-white fw-bold">Dashboard Kopi</h2>
            <p class="m-0 opacity-90">Ringkasan Utama - Pantau seluruh aktivitas operasional, mulai dari pembibitan, perawatan, hingga hasil panen dan pengiriman Kopi.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge p-3 rounded-pill bg-white text-dark fw-bold fs-7 shadow-sm">
                <i class="fa-solid fa-chart-line text-success me-1"></i> Data Operasional Kopi Terkini
            </span>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <!-- Total Luas -->
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-kopi-land">
            <div>
                <span class="metric-lbl">Total Luas Lahan</span>
                <div class="metric-val">{{ number_format($totalLuas, 1, ',', '.') }} Ha</div>
            </div>
            <span class="badge bg-success-subtle text-success metric-badge">{{ $totalBlok }} Blok</span>
        </div>
    </div>

    <!-- Total Panen -->
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-kopi-land">
            <div>
                <span class="metric-lbl">Total Hasil Panen</span>
                <div class="metric-val">{{ number_format($totalPanen, 0, ',', '.') }}</div>
            </div>
            <span class="badge bg-secondary-subtle text-secondary metric-badge">Kg</span>
        </div>
    </div>

    <!-- Total Pengiriman -->
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="stat-card-kopi-land">
            <div>
                <span class="metric-lbl">Total Pengiriman</span>
                <div class="metric-val">{{ number_format($totalPengiriman, 0, ',', '.') }}</div>
            </div>
            <span class="badge bg-info-subtle text-info metric-badge">Kg</span>
        </div>
    </div>

    <!-- Total Biaya SDM -->
    <div class="col-md-3">
        <div class="stat-card-kopi-land highlight">
            <div>
                <span class="metric-lbl">Pengeluaran Upah SDM</span>
                <div class="metric-val">Rp {{ number_format($totalSdm, 0, ',', '.') }}</div>
            </div>
            <span class="badge bg-warning text-dark metric-badge">Total</span>
        </div>
    </div>
</div>



<!-- Bottom Section: Aktivitas Kebun -->
<div class="row">
    <!-- Stok Pembibitan -->
    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 60px; height: 60px; border-radius: 50%;">
                        <i class="fa-solid fa-seedling fs-3"></i>
                    </div>
                </div>
                <h6 class="fw-bold mb-1 text-success">Stok Pembibitan</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Pengadaan bibit kopi</p>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalPembibitan, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Bibit</span></h3>
            </div>
        </div>
    </div>

    <!-- Aktivitas Penanaman -->
    <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 60px; height: 60px; border-radius: 50%;">
                        <i class="fa-solid fa-plant-wilt fs-3"></i>
                    </div>
                </div>
                <h6 class="fw-bold mb-1 text-success">Total Penanaman</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Bibit ditanam/disulam</p>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalTanam, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Pohon</span></h3>
            </div>
        </div>
    </div>

    <!-- Aktivitas Pemupukan -->
    <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-info-subtle text-info" style="width: 60px; height: 60px; border-radius: 50%;">
                        <i class="fa-solid fa-vial fs-3"></i>
                    </div>
                </div>
                <h6 class="fw-bold mb-1 text-success">Pemupukan</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Frekuensi pemupukan</p>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalPemupukan, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Kali</span></h3>
            </div>
        </div>
    </div>

    <!-- Aktivitas Obat Hama -->
    <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger" style="width: 60px; height: 60px; border-radius: 50%;">
                        <i class="fa-solid fa-bug-slash fs-3"></i>
                    </div>
                </div>
                <h6 class="fw-bold mb-1 text-success">Obat Hama</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Frekuensi obat hama</p>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalObatHama, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Kali</span></h3>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH BLOK -->
<div class="modal fade" id="tambahBlokModal" tabindex="-1" aria-labelledby="tambahBlokModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-success" id="tambahBlokModalLabel" style="font-family: 'Outfit';">Tambah Blok Lahan Kopi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('area-kebun.store') }}">
                @csrf
                <input type="hidden" name="jenis_tanaman" value="kopi">
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="nama_lahan" class="form-label fw-semibold">Nama Blok</label>
                        <input type="text" name="nama_lahan" id="nama_lahan" class="form-control" placeholder="Contoh: Blok A-03 (Arabika)" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="luas_hektar" class="form-label fw-semibold">Luas (Ha)</label>
                            <input type="number" step="0.01" name="luas_hektar" id="luas_hektar" class="form-control" placeholder="Contoh: 3.5" required>
                        </div>
                        <div class="col-6">
                            <label for="jumlah_tanaman" class="form-label fw-semibold">Populasi Tanaman (Pohon)</label>
                            <input type="number" name="jumlah_tanaman" id="jumlah_tanaman" class="form-control" placeholder="Contoh: 1750" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="usia_tanaman" class="form-label fw-semibold">Usia Pohon (Bulan)</label>
                            <input type="number" name="usia_tanaman" id="usia_tanaman" class="form-control" placeholder="Contoh: 24" required>
                        </div>
                        <div class="col-6">
                            <label for="status_lahan" class="form-label fw-semibold">Status Blok</label>
                            <select name="status_lahan" id="status_lahan" class="form-select" required>
                                <option value="produktif">Healthy (Produktif)</option>
                                <option value="perawatan">Growing (Perawatan)</option>
                                <option value="replant">Replanting (Replant)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_tanam" class="form-label fw-semibold">Tanggal Tanam</label>
                        <input type="date" name="tanggal_tanam" id="tanggal_tanam" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan / Varietas</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Contoh: Arabica Bourbon S795 / Robusta BP436" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4">Simpan Blok Kopi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Set default date picker value to today
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_tanam').val(today);

        // Soil Nutrients Chart
        const ctx = document.getElementById('soilNutrientsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['ZONA A-01', 'ZONA B-03', 'ZONA C-02', 'ZONA A-02'],
                datasets: [{
                    label: 'Indeks Nitrogen (N)',
                    data: [82, 94, 76, 88],
                    borderColor: '#15803d',
                    backgroundColor: 'rgba(21, 128, 61, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }, {
                    label: 'Indeks Kalium (K)',
                    data: [68, 85, 91, 74],
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'Inter', size: 10 } }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', weight: '600' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Inter' } }
                    }
                }
            }
        });
    });
</script>
@endsection
