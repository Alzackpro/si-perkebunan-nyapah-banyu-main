@extends('layouts.app')

@section('title', 'Input Panen Sawit')

@section('styles')
<style>
    /* Premium style for Input Panen Sawit */
    .progress-step-container {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        background-color: #ffffff;
        padding: 16px 24px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #0a3d24;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .step-circle.inactive {
        background-color: #e2e8f0;
        color: #64748b;
    }

    .step-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #0f172a;
    }

    .step-label.inactive {
        color: #64748b;
    }

    .step-line {
        flex-grow: 1;
        height: 2px;
        background-color: #e2e8f0;
        margin: 0 10px;
    }

    /* Form Design */
    .form-card-panen {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }

    .timbangan-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-top: 24px;
    }

    .timbangan-box-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #ca8a04;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    /* Side Cards */
    .side-card-mtd {
        background: linear-gradient(135deg, #135d37 0%, #0a3d24 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .side-card-mtd::after {
        content: '\f1bb';
        font-family: 'FontAwesome';
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-size: 6rem;
        color: rgba(255, 255, 255, 0.05);
        pointer-events: none;
    }

    .side-card-mtd .mtd-lbl {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #a7f3d0;
        letter-spacing: 0.8px;
    }

    .side-card-mtd .mtd-val {
        font-size: 1.8rem;
        font-weight: 800;
        margin-top: 8px;
        font-family: 'Outfit';
    }

    .side-card-pks {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
    }

    .pks-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
    }

    .pks-name {
        font-weight: 700;
        color: #0f172a;
    }

    .pks-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        background-color: #d1fae5;
        color: #065f46;
        text-transform: uppercase;
    }

    /* Table custom designs */
    .table-panen th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        background-color: #f8fafc;
    }

    .table-panen td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }

    .badge-validation {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        text-transform: uppercase;
    }

    .badge-validation.validated {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-validation.pending {
        background-color: #f1f5f9;
        color: #475569;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Input Panen Sawit</h2>
        <p class="text-muted m-0">Pencatatan hasil panen harian untuk optimalisasi produksi.</p>
    </div>
    <div class="col-auto d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="alert('Laporan berhasil diexport!');" style="padding: 8px 16px;">
            <i class="fa-solid fa-download me-1"></i> Export Report
        </button>
        <button class="btn btn-success" onclick="$('#tanggal_panen').focus();" style="padding: 8px 20px; background-color: #0a3d24; border-color: #0a3d24;">
            <i class="fa-solid fa-plus me-1"></i> Catat Baru
        </button>
    </div>
</div>

<!-- Grid Form & Side Stats -->
<div class="row mb-5">
    <!-- Form Card Left -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <!-- Progress Steps -->
        <div class="progress-step-container">
            <div class="step-circle">1</div>
            <div class="step-label">Data Batch & Jadwal</div>
            <div class="step-line"></div>
            <div class="step-circle inactive">2</div>
            <div class="step-label inactive">Detail Timbangan</div>
        </div>

        <!-- Form -->
        <div class="form-card-panen">
            <form method="POST" action="{{ route('panen.store') }}">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="tanggal_panen" class="form-label fw-bold text-dark">Jadwal Panen</label>
                        <input type="date" name="tanggal_panen" id="tanggal_panen" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="area_kebun_id" class="form-label fw-bold text-dark">Blok Kebun</label>
                        <select name="area_kebun_id" id="area_kebun_id" class="form-select" required>
                            @foreach($dataLahan as $lahan)
                                <option value="{{ $lahan->id }}">{{ $lahan->nama_lahan }} ({{ $lahan->jumlah_tanaman }} Pohon)</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Timbangan Detail Box -->
                <div class="timbangan-box">
                    <div class="timbangan-box-title">
                        <i class="fa-solid fa-scale-balanced"></i> Detail Timbangan Sawit
                    </div>
                    
                    <div class="row">
                        <!-- Gross -->
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="gross_panen" class="form-label fw-semibold text-muted">GROSS WEIGHT (KG)</label>
                            <input type="number" step="0.01" name="gross_panen" id="gross_panen" class="form-control form-control-lg bg-white" placeholder="0.00" required>
                        </div>
                        
                        <!-- Netto -->
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="netto_panen" class="form-label fw-semibold text-muted">NETTO WEIGHT (KG)</label>
                            <input type="number" step="0.01" name="netto_panen" id="netto_panen" class="form-control form-control-lg bg-white" placeholder="0.00" required>
                        </div>

                        <!-- Brondol -->
                        <div class="col-md-4">
                            <label for="panen_brondol" class="form-label fw-semibold text-muted">PANEN BRONDOL (KG)</label>
                            <input type="number" step="0.01" name="panen_brondol" id="panen_brondol" class="form-control form-control-lg bg-white" placeholder="0.00" required>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for Laravel compatibility -->
                <input type="hidden" name="harga_jual" value="2600">

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2" style="background-color: #0a3d24; border-color: #0a3d24; border-radius: 8px; font-weight: 600;">
                        Simpan Log Panen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Side Stats Cards Right -->
    <div class="col-lg-4">
        <!-- Total Brondol MTD -->
        <div class="side-card-mtd">
            <span class="mtd-lbl">TOTAL BRONDOL MTD</span>
            <div class="mtd-val">{{ number_format($totalBrondolMtd, 0, ',', '.') }} KG</div>
            <div class="mt-2 text-white-50" style="font-size: 0.8rem;">
                <i class="fa-solid fa-arrow-trend-up"></i> +12.5% vs Last Month
            </div>
        </div>

        <!-- Status Kapasitas PKS -->
        <div class="side-card-pks">
            <span class="text-muted font-monospace" style="font-size: 0.72rem; letter-spacing: 0.5px;">STATUS KAPASITAS PKS</span>
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <span class="pks-name">PKS Rimba Raya</span>
                <span class="pks-badge">Optimal</span>
            </div>
            
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-success" style="width: 75%"></div>
            </div>
            <div class="mt-2 text-muted" style="font-size: 0.8rem;">
                Capacity: <strong>45/60 Tons per Hour</strong>
            </div>
        </div>
    </div>
</div>

<!-- Table Log Panen Sawit Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-success" style="font-size: 1.05rem;">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Log Panen Sawit Terbaru
                </span>
                <button class="btn btn-sm btn-outline-secondary" type="button">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-panen align-middle m-0" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th>ID BATCH</th>
                                <th>TANGGAL</th>
                                <th>BLOK</th>
                                <th>GROSS (KG)</th>
                                <th>NETTO (KG)</th>
                                <th>BRONDOL (KG)</th>
                                <th>STATUS</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPanen as $index => $panen)
                                <tr>
                                    <td class="fw-bold text-success">
                                        #SWT-{{ date('Y', strtotime($panen->tanggal_panen)) }}-{{ str_pad($panen->id, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td>{{ date('d M Y', strtotime($panen->tanggal_panen)) }}</td>
                                    <td class="fw-bold">{{ $panen->areaKebun->nama_lahan ?? 'Blok Kebun' }}</td>
                                    <td>{{ number_format($panen->gross_panen, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-dark">{{ number_format($panen->netto_panen, 0, ',', '.') }}</td>
                                    <td class="text-success fw-semibold">{{ number_format($panen->panen_brondol, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusLabel = $panen->keterangan == 'Pending' ? 'Pending' : 'Validated';
                                        @endphp
                                        <span class="badge-validation {{ strtolower($statusLabel) }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" onclick="alert('Fungsi export / share data panen!');" class="text-muted"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                        <p class="m-0">Belum ada data pencatatan panen sawit yang masuk.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination Info -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted" style="font-size: 0.88rem;">
        Menampilkan {{ $dataPanen->firstItem() ?? 0 }}-{{ $dataPanen->lastItem() ?? 0 }} dari total {{ $dataPanen->total() }} data bulan ini
    </div>
    <div>
        {{ $dataPanen->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Set default date picker value to today
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_panen').val(today);
    });
</script>
@endsection
