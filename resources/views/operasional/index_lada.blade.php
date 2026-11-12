@extends('layouts.app')

@section('title', 'Operasional Kebun Lada')

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

    .form-card-operasional {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }

    .form-title-custom {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--primary-green);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    /* Health sidebar widget */
    .health-status-box {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
    }

    .health-status-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 20px;
    }

    .health-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .health-item:last-child {
        border-bottom: none;
    }

    .health-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
    }

    .badge-optimal { background-color: #d1fae5; color: #065f46; }
    .badge-observation { background-color: #fef3c7; color: #d97706; }

    .health-alert-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        font-size: 0.78rem;
        color: #475569;
        margin-top: 16px;
        line-height: 1.4;
    }

    /* Bottom stats widgets */
    .metric-card-custom {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
    }

    .progress-bar-custom {
        height: 6px;
        border-radius: 10px;
        background-color: #f1f5f9;
        overflow: hidden;
    }

    .progress-fill-custom {
        height: 100%;
        background-color: var(--primary-green);
        border-radius: 10px;
    }

    .green-alert-box {
        background-color: var(--primary-green);
        color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .green-alert-box::after {
        content: '\f0f3';
        font-family: 'FontAwesome';
        position: absolute;
        right: 15px;
        bottom: 5px;
        font-size: 5rem;
        color: rgba(255, 255, 255, 0.05);
        pointer-events: none;
    }

    /* Scheduler widget */
    .schedule-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
    }

    .schedule-item {
        display: flex;
        gap: 16px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .schedule-item:last-child {
        border-bottom: none;
    }

    .schedule-date-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background-color: #f1f5f9;
        font-family: 'Outfit', sans-serif;
        line-height: 1.1;
    }

    .schedule-date-box .month {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
    }

    .schedule-date-box .day {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
    }

    .schedule-info .title {
        font-weight: 700;
        font-size: 0.88rem;
        color: #0f172a;
        margin: 0;
    }

    .schedule-info .subtext {
        font-size: 0.78rem;
        color: #64748b;
        margin: 2px 0 0 0;
    }

    /* Mini widget replanted */
    .replant-mini-box {
        background-color: var(--light-green);
        border: 1px solid var(--mint-green);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 16px;
    }

    .replant-mini-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--primary-green);
        text-transform: uppercase;
        margin: 0;
    }

    .replant-mini-value {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--primary-green);
        font-family: 'Outfit', sans-serif;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Operasional Lada</h2>
        <p class="text-muted m-0">Manajemen pemeliharaan harian, log pemupukan, dan kontrol kesehatan tanaman lada di setiap blok kebun.</p>
    </div>
    <div class="col-auto">
        <button class="btn btn-success" onclick="window.location.href='{{ route('operasional.create') }}'">
            <i class="fa-solid fa-plus me-1"></i> Buat Laporan Baru
        </button>
    </div>
</div>

<!-- Grid Layout: Daily Form & Block Health -->
<div class="row mb-4">
    <!-- Form Panel Left -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="form-card-operasional">
            <div class="form-title-custom">
                <i class="fa-solid fa-screwdriver-wrench"></i> Input Kegiatan Operasional
            </div>
            
            <form method="POST" action="{{ route('operasional.store') }}" id="operasional-lada-form" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <!-- Date -->
                    <div class="col-md-6">
                        <label for="jadwal_operasional" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Jadwal Operasional</label>
                        <input type="date" name="jadwal_operasional" id="jadwal_operasional" class="form-control" required>
                    </div>
                    <!-- Block -->
                    <div class="col-md-6">
                        <label for="area_kebun_id" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Blok Lada</label>
                        <select name="area_kebun_id" id="area_kebun_id" class="form-select" required>
                            @foreach($dataLahan as $lahan)
                                <option value="{{ $lahan->id }}">{{ $lahan->nama_lahan }}</option>
                            @endforeach
                            @if(count($dataLahan) == 0)
                                <option value="">Blok A1 - Utara</option>
                                <option value="">Blok A2 - Tengah</option>
                                <option value="">Blok B1 - Selatan</option>
                            @endif
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <!-- Pupuk -->
                    <div class="col-md-6">
                        <label for="pupuk_digunakan" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">PUPUK (KG)</label>
                        <input type="number" step="0.1" name="pupuk_digunakan" id="pupuk_digunakan" class="form-control" placeholder="0.0">
                    </div>
                    <!-- Obat Hama -->
                    <div class="col-md-6">
                        <label for="obat_hama_digunakan" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">OBAT HAMA (L)</label>
                        <input type="number" step="0.1" name="obat_hama_digunakan" id="obat_hama_digunakan" class="form-control" placeholder="0.0">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <!-- Peralatan -->
                    <div class="col-md-6">
                        <label for="id_peralatan" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">PERALATAN (OPSIONAL)</label>
                        <select name="id_peralatan" id="id_peralatan" class="form-select">
                            <option value="" selected>-- Pilih Peralatan --</option>
                            @if(isset($dataPeralatan))
                                @foreach($dataPeralatan as $alat)
                                    <option value="{{ $alat->id_peralatan }}">{{ $alat->nama_peralatan ?? 'Peralatan ' . $alat->id_peralatan }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <!-- Perlengkapan -->
                    <div class="col-md-6">
                        <label for="id_perlengkapan" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">PERLENGKAPAN (OPSIONAL)</label>
                        <select name="id_perlengkapan" id="id_perlengkapan" class="form-select">
                            <option value="" selected>-- Pilih Perlengkapan --</option>
                            @if(isset($dataPerlengkapan))
                                @foreach($dataPerlengkapan as $lengkap)
                                    <option value="{{ $lengkap->id_perlengkapan }}">{{ $lengkap->nama_perlengkapan ?? 'Perlengkapan ' . $lengkap->id_perlengkapan }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <!-- Notes -->
                <div class="mb-3">
                    <label for="keterangan" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Replanting Log & Catatan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="form-control" placeholder="Detail kegiatan replanting atau catatan kondisi khusus lapangan..."></textarea>
                </div>
                
                <!-- Hidden inputs for Laravel schema compatibility -->
                <input type="hidden" name="jenis_operasional" value="Pemupukan">
                <input type="hidden" name="status_operasional" value="Selesai">
                <input type="hidden" name="biaya_operasional" value="1500000">
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="reset" class="btn btn-light px-4" style="border: 1px solid #cbd5e1; border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-success px-4" style="background-color: var(--primary-green); border-color: var(--primary-green); border-radius: 8px; font-weight: 600;">
                        Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Health status Right -->
    <div class="col-lg-4">
        <div class="health-status-box">
            <div class="health-status-title">
                <i class="fa-solid fa-heart-pulse me-2 text-danger"></i>Kesehatan Blok
            </div>
            
            <div class="health-list">
                <div class="health-item">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background-color: var(--primary-green); border-radius: 50%;">A1</div>
                        <span class="fw-semibold text-dark">Blok Utara</span>
                    </div>
                    <span class="health-badge badge-optimal">Optimal</span>
                </div>
                
                <div class="health-item">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background-color: #d97706; border-radius: 50%;">A2</div>
                        <span class="fw-semibold text-dark">Blok Tengah</span>
                    </div>
                    <span class="health-badge badge-observation">observation</span>
                </div>
                
                <div class="health-item">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background-color: var(--primary-green); border-radius: 50%;">B1</div>
                        <span class="fw-semibold text-dark">Blok Selatan</span>
                    </div>
                    <span class="health-badge badge-optimal">Optimal</span>
                </div>
            </div>
            
            <div class="health-alert-box">
                <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i>
                Blok Tengah menunjukkan penurunan efikasi obat hama sebesar <strong>5%</strong>. Disarankan pengkodian drainase.
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row: Efficacy stats & scheduler -->
<div class="row">
    <!-- Pest control stats -->
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="metric-card-custom d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-success" style="font-size: 0.95rem;">Pest Control Efficacy</span>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.72rem; font-weight: 700;">Excellent</span>
                </div>
                
                <div class="d-flex align-items-baseline gap-2 mb-2">
                    <h3 class="fw-extrabold m-0" style="font-family: 'Outfit'; font-size: 1.85rem;">94.2%</h3>
                    <span class="text-success fw-semibold" style="font-size: 0.85rem;"><i class="fa-solid fa-arrow-trend-up"></i> +2.6%</span>
                </div>
                <p class="text-muted" style="font-size: 0.78rem; line-height: 1.4;">Performa pestisida lada dalam 30 hari terakhir terhadap serangga pengerek batang.</p>
            </div>
            
            <div class="progress-bar-custom mt-3">
                <div class="progress-fill-custom" style="width: 94.2%"></div>
            </div>
        </div>
    </div>
    
    <!-- Tanaman sehat banner -->
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="green-alert-box d-flex flex-column justify-content-between">
            <span class="text-uppercase font-monospace text-white-50" style="font-size: 0.7rem; letter-spacing: 0.5px;">HEALTH ALERT</span>
            <div>
                <h4 class="fw-bold m-0" style="font-family: 'Outfit'; font-size: 1.35rem; color:#ffffff;">Tanaman Sehat</h4>
                <p class="text-white-50 mt-2 mb-0" style="font-size: 0.82rem; line-height: 1.5;">Semua blok utama dalam kondisi optimal. Tidak ditemukan indikasi penyakit akar.</p>
            </div>
            
            <a href="#" class="text-white fw-bold text-decoration-none" style="font-size: 0.85rem; margin-top: 16px;">
                Lihat Detail Diagnosa <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Calendar scheduler -->
    <div class="col-lg-4">
        <div class="schedule-card">
            <span class="fw-bold text-success" style="font-size: 0.95rem;"><i class="fa-solid fa-calendar-days me-2"></i>Jadwal Pemupukan</span>
            
            <div class="schedule-list mt-3">
                <!-- Item 1 -->
                <div class="schedule-item">
                    <div class="schedule-date-box">
                        <span class="month">Sep</span>
                        <span class="day">24</span>
                    </div>
                    <div class="schedule-info">
                        <div class="title">Pemupukan NPK Fase 2</div>
                        <div class="subtext">Blok A1 • 120 Tanaman</div>
                    </div>
                </div>
                <!-- Item 2 -->
                <div class="schedule-item">
                    <div class="schedule-date-box">
                        <span class="month">Sep</span>
                        <span class="day">28</span>
                    </div>
                    <div class="schedule-info">
                        <div class="title">Distribusi Organik Cair</div>
                        <div class="subtext">Seluruh Blok • Lada</div>
                    </div>
                </div>
                <!-- Item 3 -->
                <div class="schedule-item">
                    <div class="schedule-date-box">
                        <span class="month">Okt</span>
                        <span class="day">02</span>
                    </div>
                    <div class="schedule-info">
                        <div class="title">Injeksi Nutrisi Akar</div>
                        <div class="subtext">Blok B1 & B2</div>
                    </div>
                </div>
            </div>
            
            <!-- Replanted mini block inside calendar panel -->
            <div class="replant-mini-box">
                <span class="replant-mini-title">Bibit Replanted</span>
                <span class="replant-mini-value">450 PCS</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Set date default value to today
        const today = new Date().toISOString().split('T')[0];
        $('#jadwal_operasional').val(today);
    });
</script>
@endsection
