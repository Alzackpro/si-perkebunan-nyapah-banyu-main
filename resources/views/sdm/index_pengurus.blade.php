@extends('layouts.app')

@section('title', 'Upah Pekerja Sawit')

@section('styles')
<style>
    /* Premium style for Upah Pekerja Sawit */
    .stat-card-wages {
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

    .stat-card-wages.payout {
        background-color: #0a3d24;
        color: #ffffff;
        border: none;
    }

    .stat-card-wages.payout .metric-lbl {
        color: #a7f3d0;
    }

    .stat-card-wages.payout .metric-val {
        color: #ffffff;
    }

    .stat-card-wages .metric-lbl {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card-wages .metric-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 4px;
        font-family: 'Outfit', sans-serif;
    }

    .stat-card-wages .metric-subtext {
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 4px;
    }

    /* Form card */
    .form-card-upah {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }

    .estimate-box {
        background-color: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 16px;
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .estimate-lbl {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
    }

    .estimate-val {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        font-family: 'Outfit';
    }

    /* Table custom designs */
    .table-wages th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        background-color: #f8fafc;
    }

    .table-wages td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.88rem;
    }

    .worker-initial-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #d1fae5;
        color: #065f46;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
    }

    /* Insight Banner */
    .insight-alert-box {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        gap: 12px;
        align-items: start;
        margin-bottom: 24px;
    }

    .insight-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #065f46;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 4px 0;
    }

    /* Branding Banner Landscape */
    .landscape-banner-sawit {
        border-radius: 16px;
        height: 120px;
        background: linear-gradient(rgba(10, 61, 36, 0.4), rgba(10, 61, 36, 0.6)), url('https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&q=80&w=1200&h=300');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        padding: 0 32px;
        color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .landscape-banner-sawit h3 {
        font-family: 'Outfit';
        font-weight: 800;
        margin: 0;
        letter-spacing: 0.5px;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Upah Pekerja Sawit</h2>
        <p class="text-muted m-0">Catat kerja harian buruh sawit dan monitor anggaran payroll.</p>
    </div>
    <div class="col-auto">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Cari pekerja...">
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <!-- Total Wages Paid -->
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="stat-card-wages">
            <div>
                <span class="metric-lbl">TOTAL WAGES PAID (MTD)</span>
                <div class="metric-val">Rp {{ number_format($totalWagesMtd / 1000, 3, '.', '.') }}k</div>
                <div class="metric-subtext text-success">
                    <i class="fa-solid fa-arrow-trend-up"></i> +12% vs last month
                </div>
            </div>
            <div class="fs-1 text-success opacity-25"><i class="fa-solid fa-wallet"></i></div>
        </div>
    </div>

    <!-- Active Workers -->
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="stat-card-wages">
            <div>
                <span class="metric-lbl">ACTIVE WORKERS TODAY</span>
                <div class="metric-val">{{ $activeWorkersCount }} Pekerja</div>
                <div class="metric-subtext text-muted">
                    85% attendance rate
                </div>
            </div>
            <div class="fs-1 text-secondary opacity-25"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>

    <!-- Next Payout Date -->
    <div class="col-md-4">
        <div class="stat-card-wages payout">
            <div>
                <span class="metric-lbl">NEXT PAYOUT DATE</span>
                <div class="metric-val">25 Okt</div>
                <div class="metric-subtext text-white-50">
                    4 days left
                </div>
            </div>
            <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="alert('Membuka review payroll!');">
                Review Payroll
            </button>
        </div>
    </div>
</div>

<!-- Grid Form & Table -->
<div class="row mb-4">
    <!-- Form Left Panel -->
    <div class="col-lg-5 mb-4 mb-lg-0">
        <div class="form-card-upah">
            <h5 class="fw-bold text-success mb-4" style="font-family: 'Outfit';">
                <i class="fa-solid fa-file-pen me-2"></i>Log Upah Baru
            </h5>
            
            <form method="POST" action="{{ route('sdm.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="nama_pekerja" class="form-label fw-semibold">Nama Pekerja</label>
                    <input type="text" name="nama_pekerja" id="nama_pekerja" class="form-control" placeholder="Contoh: Budi Santoso" required>
                </div>

                <div class="mb-3">
                    <label for="jenis_pekerjaan" class="form-label fw-semibold">Jenis Pekerjaan</label>
                    <select name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-select" required>
                        <option value="Pemanen">Pemanen</option>
                        <option value="Tukang Pupuk">Tukang Pupuk</option>
                        <option value="Tukang Semprot">Tukang Semprot</option>
                        <option value="Maintenance Jalan">Maintenance Lahan</option>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="jumlah_hari" class="form-label fw-semibold">Hari Kerja</label>
                        <input type="number" name="jumlah_hari" id="jumlah_hari" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="col-6">
                        <label for="upah_harian" class="form-label fw-semibold">Tarif Harian (Rp)</label>
                        <input type="number" name="upah_harian" id="upah_harian" class="form-control" value="150000" min="1" required>
                    </div>
                </div>

                <!-- Hidden inputs for default Laravel SDM compatibility -->
                <input type="hidden" name="tanggal_kerja" id="tanggal_kerja" value="{{ date('Y-m-d') }}">

                <!-- Estimasi Box -->
                <div class="estimate-box">
                    <span class="estimate-lbl">Estimasi Total Upah:</span>
                    <span class="estimate-val" id="estimasi_total">Rp 150.000</span>
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 mt-4" style="background-color: #0a3d24; border-color: #0a3d24; border-radius: 12px; font-weight: 700;">
                    <i class="fa-solid fa-plus me-1"></i> Catat Upah
                </button>
            </form>
        </div>
    </div>

    <!-- Table Right Panel -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-dark" style="font-size: 0.95rem;">Log Pekerja Terbaru</span>
                <button class="btn btn-sm btn-light border" type="button"><i class="fa-solid fa-sliders"></i></button>
            </div>
            <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div class="table-responsive">
                    <table class="table table-wages align-middle m-0">
                        <thead>
                            <tr>
                                <th>PEKERJA</th>
                                <th>TIPE</th>
                                <th>DURASI</th>
                                <th>TARIF</th>
                                <th>TOTAL UPAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPekerja as $pekerja)
                                @php
                                    $initials = '';
                                    $parts = explode(' ', $pekerja->nama_pekerja);
                                    foreach ($parts as $p) {
                                        $initials .= strtoupper(substr($p, 0, 1));
                                    }
                                    $initials = substr($initials, 0, 2);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="worker-initial-avatar">{{ $initials }}</div>
                                            <span class="fw-bold text-dark">{{ $pekerja->nama_pekerja }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">
                                            {{ $pekerja->jenis_pekerjaan }}
                                        </span>
                                    </td>
                                    <td>{{ $pekerja->jumlah_hari }} Hari</td>
                                    <td>Rp {{ number_format($pekerja->upah_harian, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($pekerja->total_gaji, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada pencatatan upah pekerja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <span class="text-muted" style="font-size: 0.8rem;">
                        Menampilkan {{ $dataPekerja->firstItem() ?? 0 }}-{{ $dataPekerja->lastItem() ?? 0 }} dari total {{ $dataPekerja->total() }} pekerja
                    </span>
                    <div>
                        {{ $dataPekerja->appends(request()->input())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Insight Kebun Alert -->
<div class="insight-alert-box">
    <i class="fa-solid fa-lightbulb text-success fs-4"></i>
    <div>
        <h6 class="insight-title">Insight Kebun</h6>
        <p class="m-0 text-dark" style="font-size: 0.85rem; line-height: 1.4;">
            Kenaikan upah pemanen biasanya berkorelasi dengan puncak musim panen di Blok C1-C5.
        </p>
    </div>
</div>

<!-- Landscape Banner Bottom -->
<div class="landscape-banner-sawit">
    <h3>Manajemen Terpadu Nyapah Banyu</h3>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto Estimation Wages calculation
        $('#jumlah_hari, #upah_harian').on('input change', function() {
            const hari = parseFloat($('#jumlah_hari').val()) || 0;
            const tarif = parseFloat($('#upah_harian').val()) || 0;
            const total = hari * tarif;
            
            // Format to Indonesian currency Rp
            const formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(total);
            
            $('#estimasi_total').text(formatted.replace('IDR', 'Rp'));
        });

        // Set date to today
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_kerja').val(today);
    });
</script>
@endsection
