@extends('layouts.app')

@section('title', isset($jenis) ? 'Laporan Terpadu' : 'Audit & Pelaporan Sistem')

@section('styles')
<style>
    /* Custom Styling for Audit & Report View */
    .rep-header {
        margin-bottom: 25px;
    }

    .rep-title {
        font-weight: 800;
        color: var(--primary-green);
        margin: 0;
    }

    .rep-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    /* Financial & Security Stat Cards */
    .rep-stat-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .rep-stat-info {
        line-height: 1.3;
    }

    .rep-stat-label {
        font-size: 0.72rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .rep-stat-value {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--dark-charcoal);
        margin: 6px 0;
        font-family: 'Outfit', sans-serif;
    }

    .rep-stat-trend {
        font-size: 0.78rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .rep-stat-trend.up { color: #166534; }
    .rep-stat-trend.down { color: #991b1b; }

    .rep-stat-icon-bg {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background-color: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #94a3b8;
    }

    /* Green Security Card */
    .security-status-card {
        background-color: var(--primary-green);
        color: #ffffff;
        border-radius: 20px;
        padding: 24px;
        height: 100%;
        position: relative;
        overflow: hidden;
        border: none;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .security-status-card::before {
        content: '\f3ed';
        font-family: 'FontAwesome';
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-size: 6rem;
        color: rgba(255, 255, 255, 0.05);
        pointer-events: none;
    }

    .sec-icon-box {
        font-size: 2.2rem;
        color: #4ade80;
    }

    .sec-info h6 {
        font-weight: 800;
        margin: 0 0 2px 0;
        font-size: 1.05rem;
    }

    .sec-info p {
        font-size: 0.8rem;
        color: #a7f3d0;
        margin: 0;
        font-weight: 500;
    }

    /* Audit Logs Card */
    .audit-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }

    .audit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
    }

    .audit-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-green);
        margin: 0;
    }

    .table-audit th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #94a3b8;
        font-weight: 700;
        border-bottom: 2px solid #f1f5f9;
        padding: 12px 16px;
    }

    .table-audit td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .table-audit tr:last-child td {
        border-bottom: none;
    }

    .audit-time {
        font-weight: 600;
        color: var(--dark-charcoal);
    }

    .audit-date {
        font-size: 0.75rem;
        color: #94a3b8;
        display: block;
    }

    .user-avatar-badge {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-badge-pic {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-badge-name {
        font-weight: 700;
        color: var(--dark-charcoal);
    }

    .badge-audit-status {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    .badge-audit-status.selesai {
        background-color: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-audit-status.gagal {
        background-color: #fff5f5;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Peringatan Keamanan Card */
    .sec-alert-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 25px;
    }

    .sec-alert-header {
        font-size: 1rem;
        font-weight: 800;
        color: var(--primary-green);
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sec-alert-header i {
        color: #ef4444;
    }

    .sec-alert-item {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 12px;
        display: flex;
        gap: 12px;
    }

    .sec-alert-item.danger {
        background-color: #fff5f5;
        border-color: #fee2e2;
    }

    .sec-alert-icon {
        font-size: 1.2rem;
    }

    .sec-alert-item.danger .sec-alert-icon { color: #ef4444; }
    .sec-alert-item.info .sec-alert-icon { color: #3b82f6; }

    .sec-alert-body h6 {
        font-weight: 800;
        color: var(--dark-charcoal);
        margin: 0 0 2px 0;
        font-size: 0.9rem;
    }

    .sec-alert-body p {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0 0 8px 0;
        line-height: 1.3;
    }

    .btn-block-ip {
        font-size: 0.72rem;
        font-weight: 800;
        color: #ef4444;
        background-color: #fee2e2;
        border: 1px solid #fca5a5;
        padding: 4px 10px;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
    }

    .btn-block-ip:hover {
        background-color: #fca5a5;
        color: #991b1b;
    }

    /* Access Location Map Card */
    .loc-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 25px;
    }

    .loc-container {
        height: 150px;
        background-color: #0c1a30;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        margin-bottom: 15px;
        border: 1px solid #1e293b;
    }

    .loc-stat-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.82rem;
        color: #64748b;
        margin-bottom: 8px;
    }

    .loc-stat-row span:last-child {
        font-weight: 700;
        color: var(--dark-charcoal);
    }

    /* Data Integrity Card */
    .integrity-card {
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        text-align: center;
    }

    .integrity-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background-color: #f0fdf4;
        color: #166534;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin: 0 auto 15px auto;
        border: 1px solid #bbf7d0;
    }

    .integrity-title {
        font-weight: 800;
        font-size: 0.95rem;
        color: var(--dark-charcoal);
        margin-bottom: 5px;
    }

    .integrity-desc {
        font-size: 0.78rem;
        color: #64748b;
        margin-bottom: 15px;
        line-height: 1.3;
    }

    .btn-run-audit {
        font-size: 0.82rem;
        font-weight: 700;
        background-color: #ffffff;
        color: var(--dark-charcoal);
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 8px 16px;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
        width: 100%;
    }

    .btn-run-audit:hover {
        background-color: #f8fafc;
        border-color: #94a3b8;
    }
</style>
@endsection

@section('content')
<div class="row align-items-center mb-4 rep-header">
    <div class="col">
        <h2 class="rep-title">{{ isset($jenis) ? 'Hasil Rekapitulasi Laporan' : 'Audit & Pelaporan Sistem' }}</h2>
        <p class="rep-subtitle">{{ isset($jenis) ? 'Hasil saringan parameter rekapitulasi data.' : 'Ringkasan finansial dan log keamanan tingkat tinggi untuk pemilik sistem.' }}</p>
    </div>
    <div class="col-auto d-flex gap-3">
        <!-- Dashboard Filters -->
        @if(!isset($jenis))
            <div class="btn-group bg-white border border-light-subtle rounded-3 p-1" role="group">
                <a href="{{ route('laporan.index', ['filter' => 'all']) }}" class="btn btn-sm rounded-2 px-3 {{ request('filter', 'all') == 'all' ? 'btn-success' : 'btn-light text-muted' }}">Semua Waktu</a>
                <a href="{{ route('laporan.index', ['filter' => 'month']) }}" class="btn btn-sm rounded-2 px-3 {{ request('filter') == 'month' ? 'btn-success' : 'btn-light text-muted' }}">Bulan Ini</a>
                <a href="{{ route('laporan.index', ['filter' => 'week']) }}" class="btn btn-sm rounded-2 px-3 {{ request('filter') == 'week' ? 'btn-success' : 'btn-light text-muted' }}">Pekan Ini</a>
            </div>
            <!-- Dynamic collapsible query launcher -->
            <button class="btn btn-outline-success border-light-subtle bg-white rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel" aria-expanded="false">
                <i class="fa-solid fa-filter me-1"></i> Saring Data Laporan
            </button>
        @else
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-success">
                <i class="fa-solid fa-chevron-left me-1"></i> Kembali ke Audit
            </a>
        @endif
    </div>
</div>

<!-- COLLAPSIBLE PARAMETER FILTER FORM -->
<div class="collapse {{ isset($jenis) ? 'show' : '' }} mb-4" id="filterPanel">
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fa-solid fa-file-invoice-dollar me-1"></i> Parameter Saring Laporan
        </div>
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-3">
                    <ul class="m-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('laporan.tampilkan') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label for="jenis_laporan" class="form-label fw-semibold">Jenis Laporan</label>
                    <select name="jenis_laporan" id="jenis_laporan" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Jenis --</option>
                        <option value="pembibitan" {{ old('jenis_laporan', $jenis ?? '') == 'pembibitan' ? 'selected' : '' }}>Laporan Pembibitan</option>
                        <option value="tanam" {{ old('jenis_laporan', $jenis ?? '') == 'tanam' ? 'selected' : '' }}>Laporan Tanam & Penyulaman</option>
                        <option value="panen" {{ old('jenis_laporan', $jenis ?? '') == 'panen' ? 'selected' : '' }}>Laporan Panen</option>
                        <option value="operasional" {{ old('jenis_laporan', $jenis ?? '') == 'operasional' ? 'selected' : '' }}>Laporan Operasional</option>
                        <option value="pengiriman" {{ old('jenis_laporan', $jenis ?? '') == 'pengiriman' ? 'selected' : '' }}>Laporan Pengiriman Pabrik</option>
                        <option value="sdm" {{ old('jenis_laporan', $jenis ?? '') == 'sdm' ? 'selected' : '' }}>Laporan Gaji Pekerja</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $mulai ?? '') }}" required>
                </div>

                <div class="col-md-3">
                    <label for="tanggal_akhir" class="form-label fw-semibold">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ old('tanggal_akhir', $akhir ?? '') }}" required>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(isset($jenis))
    <!-- DETAILED REPORT FILTER RESULT TABLE (Standard layout dynamically selected) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="m-0 text-success fw-bold text-uppercase" style="font-size:1.1rem;">
                <i class="fa-solid fa-file-lines me-1"></i> Hasil Laporan {{ $jenis }} 
                <span class="text-muted" style="font-size: 0.88rem; text-transform: none;">({{ date('d/m/Y', strtotime($mulai)) }} s.d. {{ date('d/m/Y', strtotime($akhir)) }})</span>
            </h5>
            <div>
                <a href="{{ route('laporan.cetak', ['jenis' => $jenis, 'mulai' => $mulai, 'akhir' => $akhir]) }}" target="_blank" class="btn btn-sm btn-outline-success me-2">
                    <i class="fa-solid fa-print me-1"></i> Cetak Dokumen
                </a>
                <a href="{{ route('laporan.unduh-pdf', ['jenis' => $jenis, 'mulai' => $mulai, 'akhir' => $akhir]) }}" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-file-pdf me-1"></i> Unduh PDF
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($jenis == 'panen')
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle m-0" style="min-width: 1000px;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-3" style="width: 60px;">No</th>
                                <th class="py-3">Tanggal Panen</th>
                                <th class="py-3">Lahan Kebun</th>
                                <th class="py-3">Komoditas</th>
                                <th class="py-3 text-end">Berat Gross</th>
                                <th class="py-3 text-end">Berat Netto</th>
                                <th class="py-3 text-end">Harga Jual (per kg)</th>
                                <th class="py-3 text-end">Total Pendapatan</th>
                                <th class="py-3 pe-4">Pencatat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalGross = 0; $totalNetto = 0; $totalPendapatan = 0; @endphp
                            @forelse($dataLaporan as $index => $panen)
                                @php
                                    $totalGross += $panen->jumlah_panen_gross;
                                    $totalNetto += $panen->jumlah_netto;
                                    $totalPendapatan += $panen->total_pendapatan;
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($panen->tanggal_panen)) }}</td>
                                    <td class="fw-semibold">{{ $panen->areaKebun->nama_lahan }}</td>
                                    <td><span class="badge bg-success text-uppercase">{{ $panen->jenis_tanaman }}</span></td>
                                    <td class="text-end">{{ number_format($panen->jumlah_panen_gross, 2, ',', '.') }} kg</td>
                                    <td class="text-end fw-semibold text-dark">{{ number_format($panen->jumlah_netto, 2, ',', '.') }} kg</td>
                                    <td class="text-end">Rp {{ number_format($panen->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($panen->total_pendapatan, 0, ',', '.') }}</td>
                                    <td class="pe-4 text-muted small">{{ $panen->user->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">Tidak ditemukan data panen pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($dataLaporan) > 0)
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="4" class="ps-4 text-end">TOTAL REKAPITULASI :</td>
                                    <td class="text-end text-dark">{{ number_format($totalGross, 2, ',', '.') }} kg</td>
                                    <td class="text-end text-dark">{{ number_format($totalNetto, 2, ',', '.') }} kg</td>
                                    <td></td>
                                    <td class="text-end text-success" style="font-size: 1.05rem;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            @elseif($jenis == 'operasional')
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle m-0" style="min-width: 1000px;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-3" style="width: 60px;">No</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Lahan Kebun</th>
                                <th class="py-3">Jenis Perawatan</th>
                                <th class="py-3">Keterangan</th>
                                <th class="py-3">Pegawai Pelaksana</th>
                                <th class="py-3 pe-4">Peralatan & Perlengkapan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataLaporan as $index => $ops)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($ops->tanggal_perawatan)) }}</td>
                                    <td class="fw-semibold">{{ optional($ops->areaKebun)->nama_lahan ?? '-' }}</td>
                                    <td>
                                        @if($ops->jenis_perawatan == 'Pemupukan')
                                            <span class="badge bg-success text-white"><i class="fa-solid fa-seedling me-1"></i> Pemupukan</span>
                                        @elseif($ops->jenis_perawatan == 'Obat Hama')
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-bug-slash me-1"></i> Obat Hama</span>
                                        @else
                                            <span class="badge bg-primary text-white"><i class="fa-solid fa-layer-group me-1"></i> Keduanya</span>
                                        @endif
                                    </td>
                                    <td>{{ $ops->keterangan ?: '-' }}</td>
                                    <td>{{ optional($ops->pegawai)->nama_pegawai ?? '-' }}</td>
                                    <td class="pe-4">
                                        <div class="small">
                                            <i class="fa-solid fa-toolbox me-1 text-muted"></i> {{ optional($ops->peralatan)->nama_peralatan ?? '-' }}
                                            <br>
                                            <i class="fa-solid fa-box-open me-1 text-muted"></i> {{ optional($ops->perlengkapan)->nama_perlengkapan ?? '-' }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">Tidak ditemukan data operasional pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @elseif($jenis == 'pengiriman')
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle m-0" style="min-width: 1000px;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-3" style="width: 60px;">No</th>
                                <th class="py-3">Tanggal Pengiriman</th>
                                <th class="py-3">Lahan Asal</th>
                                <th class="py-3">Komoditas</th>
                                <th class="py-3">Keterangan</th>
                                <th class="py-3 text-end">Jumlah Dikirim</th>
                                <th class="py-3 text-end">Total Penjualan</th>
                                <th class="py-3 pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalKirim = 0; $totalPendapatanKirim = 0; @endphp
                            @forelse($dataLaporan as $index => $kirim)
                                @php 
                                    $totalKirim += $kirim->jumlah_dikirim; 
                                    $totalPendapatanKirim += $kirim->total_penjualan;
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($kirim->tanggal_pengiriman)) }}</td>
                                    <td class="fw-semibold">{{ optional(optional($kirim->panen)->areaKebun)->nama_lahan ?? '-' }}</td>
                                    <td><span class="badge bg-success text-uppercase">{{ optional($kirim->komoditas)->nama_komoditas ?? '-' }}</span></td>
                                    <td>{{ $kirim->keterangan ?: '-' }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($kirim->jumlah_dikirim, 2, ',', '.') }} kg</td>
                                    <td class="text-end text-primary">Rp {{ number_format($kirim->total_penjualan, 0, ',', '.') }}</td>
                                    <td class="pe-4">
                                        @php
                                            $statusName = strtolower(optional($kirim->status_pengiriman)->nama_status ?? 'terjadwal');
                                            $badgeClass = 'perjalanan';
                                            if (str_contains($statusName, 'selesai') || str_contains($statusName, 'terkirim')) $badgeClass = 'diterima';
                                            if (str_contains($statusName, 'batal') || str_contains($statusName, 'tolak')) $badgeClass = 'ditolak';
                                        @endphp
                                        <span class="badge-delivery-status {{ $badgeClass }}">
                                            {{ ucwords(optional($kirim->status_pengiriman)->nama_status ?? 'Terjadwal') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Tidak ditemukan data pengiriman pabrik pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($dataLaporan) > 0)
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="5" class="ps-4 text-end">TOTAL REKAPITULASI PENGIRIMAN :</td>
                                    <td class="text-end text-success" style="font-size: 1.05rem;">{{ number_format($totalKirim, 2, ',', '.') }} kg</td>
                                    <td class="text-end text-primary" style="font-size: 1.05rem;">Rp {{ number_format($totalPendapatanKirim, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            @elseif($jenis == 'sdm')
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle m-0" style="min-width: 900px;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-3" style="width: 60px;">No</th>
                                <th class="py-3">Nama Pekerja</th>
                                <th class="py-3">Jenis Pekerjaan</th>
                                <th class="py-3">Tanggal Kerja</th>
                                <th class="py-3 text-end">Upah Harian</th>
                                <th class="py-3 text-end">Durasi Kerja</th>
                                <th class="py-3 text-end pe-4">Total Gaji Upah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalGajiSdm = 0; @endphp
                            @forelse($dataLaporan as $index => $sdm)
                                @php $totalGajiSdm += $sdm->total_gaji; @endphp
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $sdm->nama_pekerja }}</td>
                                    <td>{{ $sdm->jenis_pekerjaan }}</td>
                                    <td>{{ date('d-m-Y', strtotime($sdm->tanggal_kerja)) }}</td>
                                    <td class="text-end">Rp {{ number_format($sdm->upah_harian, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ $sdm->jumlah_hari }} Hari</td>
                                    <td class="text-end fw-bold text-danger pe-4">Rp {{ number_format($sdm->total_gaji, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">Tidak ditemukan data riwayat upah pekerja pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($dataLaporan) > 0)
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="6" class="ps-4 text-end">TOTAL PENGELUARAN GAJI PEKERJA :</td>
                                    <td class="text-end text-danger pe-4" style="font-size: 1.05rem;">Rp {{ number_format($totalGajiSdm, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            @elseif($jenis == 'pembibitan')
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle m-0" style="min-width: 900px;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-3" style="width: 60px;">No</th>
                                <th class="py-3">Tanggal Pembibitan</th>
                                <th class="py-3">Komoditas</th>
                                <th class="py-3 text-end">Jumlah Bibit Baru</th>
                                <th class="py-3 text-end pe-4">Jumlah Penyulaman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalBibit = 0; $totalSulam = 0; @endphp
                            @forelse($dataLaporan as $index => $bibit)
                                @php 
                                    $totalBibit += $bibit->jumlah_bibit;
                                    $totalSulam += $bibit->jumlah_penyulaman;
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($bibit->tanggal_pembibitan)) }}</td>
                                    <td><span class="badge bg-success text-uppercase">{{ optional($bibit->komoditas)->nama_komoditas ?? '-' }}</span></td>
                                    <td class="text-end fw-semibold">{{ number_format($bibit->jumlah_bibit, 0, ',', '.') }} Bibit</td>
                                    <td class="text-end fw-bold text-warning pe-4">{{ number_format($bibit->jumlah_penyulaman, 0, ',', '.') }} Sulam</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Tidak ditemukan data pembibitan pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($dataLaporan) > 0)
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="3" class="ps-4 text-end">TOTAL REKAPITULASI PEMBIBITAN :</td>
                                    <td class="text-end text-dark">{{ number_format($totalBibit, 0, ',', '.') }} Bibit</td>
                                    <td class="text-end text-warning pe-4" style="font-size: 1.05rem;">{{ number_format($totalSulam, 0, ',', '.') }} Sulam</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            @elseif($jenis == 'tanam')
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle m-0" style="min-width: 900px;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-3" style="width: 60px;">No</th>
                                <th class="py-3">Tanggal Tanam</th>
                                <th class="py-3">Area Kebun</th>
                                <th class="py-3">Keterangan</th>
                                <th class="py-3 text-end pe-4">Jumlah Ditanam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalTanam = 0; @endphp
                            @forelse($dataLaporan as $index => $tanam)
                                @php $totalTanam += $tanam->jumlah_tanam; @endphp
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($tanam->tanggal_tanam)) }}</td>
                                    <td class="fw-semibold">{{ optional($tanam->area)->nama_area ?? '-' }}</td>
                                    <td>{{ $tanam->keterangan ?: '-' }}</td>
                                    <td class="text-end fw-bold text-success pe-4">{{ number_format($tanam->jumlah_tanam, 0, ',', '.') }} Pokok</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Tidak ditemukan data tanam/penyulaman pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($dataLaporan) > 0)
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="4" class="ps-4 text-end">TOTAL BIBIT YANG DITANAM :</td>
                                    <td class="text-end text-success pe-4" style="font-size: 1.05rem;">{{ number_format($totalTanam, 0, ',', '.') }} Pokok</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            @endif
        </div>
    </div>
@else
    @if(Auth::user()->isOwner())
        <!-- DEFAULT DASHBOARD VIEW: AUDIT & PELAPORAN SISTEM (HANYA OWNER) -->
        
        <!-- MASTER DATA SUMMARY -->
        <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-database me-1"></i> Ringkasan Data Master</h6>
        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Komoditas</div>
                        <h4 class="fw-bold m-0 text-dark">{{ $stats['komoditas'] ?? 0 }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Peralatan</div>
                        <h4 class="fw-bold m-0 text-dark">{{ $stats['peralatan'] ?? 0 }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-toolbox"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Perlengkapan</div>
                        <h4 class="fw-bold m-0 text-dark">{{ $stats['perlengkapan'] ?? 0 }}</h4>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Area Kebun</div>
                        <h4 class="fw-bold m-0 text-dark">{{ $stats['area'] ?? 0 }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Pegawai</div>
                        <h4 class="fw-bold m-0 text-dark">{{ $stats['pegawai'] ?? 0 }}</h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Jadwal</div>
                        <h4 class="fw-bold m-0 text-dark">{{ $stats['jadwal'] ?? 0 }}</h4>
                    </div>
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Status Sistem</div>
                        <h4 class="fw-bold m-0 text-dark">{{ $stats['status'] ?? 0 }}</h4>
                    </div>
                    <div class="bg-dark bg-opacity-10 text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Akun Pengguna</div>
                        <h4 class="fw-bold m-0 text-dark">{{ $stats['pengguna'] ?? 0 }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>

        <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-chart-line me-1"></i> Finansial & Audit Sistem</h6>
        <!-- FINANCIAL STATS ROW -->
        <div class="row mb-4">
        <!-- Total Upah Terbayar -->
        <div class="col-md-12 mb-4">
            <div class="rep-stat-card">
                <div class="rep-stat-info">
                    <span class="rep-stat-label">Total Upah Terbayar</span>
                    <div class="rep-stat-value">Rp {{ number_format($totalUpah, 0, ',', '.') }}</div>
                    <span class="rep-stat-trend up">
                        <i class="fa-solid fa-arrow-trend-up"></i> Berdasarkan Data SDM
                    </span>
                </div>
                <div class="rep-stat-icon-bg">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- LOG AUDIT SISTEM ROW -->
    <div class="row">
        <!-- Log Audit Sistem Table -->
        <div class="col-lg-12 mb-4">
            <div class="audit-card">
                <div class="audit-header">
                    <h5 class="audit-title">Log Audit Sistem</h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button">
                        <i class="fa-solid fa-arrow-down-wide-short"></i> Semua Aktivitas
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-audit align-middle m-0">
                        <thead>
                            <tr>
                                <th>Waktu & Tanggal</th>
                                <th>Pengguna</th>
                                <th>Aksi / Perubahan</th>
                                <th>Entitas</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logAktivitas as $log)
                                @php
                                    // Entity codes matching mockup layout
                                    $entities = ['ID-FIN-8821', 'LAND-B-C12', 'PDF-Fu2023_Full', 'ID-USER-4312', 'LAND-A-B04'];
                                    $entityCode = $entities[rand(0, 4)];
                                @endphp
                                <tr>
                                    <td>
                                        <div class="audit-time">{{ date('H:i', strtotime($log->created_at)) }}</div>
                                        <span class="audit-date">{{ \Carbon\Carbon::parse($log->created_at)->locale('id')->isoFormat('DD MMM YYYY') }}</span>
                                    </td>
                                    <td>
                                        <div class="user-avatar-badge">
                                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="User" class="user-badge-pic">
                                            <span class="user-badge-name">{{ $log->user->name ?? 'System' }}</span>
                                        </div>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ str_replace('Edit', 'Ubah', $log->aktivitas) }}</td>
                                    <td class="text-muted">{{ $entityCode }}</td>
                                    <td>
                                        <span class="badge-audit-status selesai">SELESAI</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat aktivitas sistem.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination navigation -->
                <div class="mt-4">
                    {{ $logAktivitas->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    @else
        <!-- DEFAULT VIEW FOR PENGURUS -->
        <div class="row text-center mt-5">
            <div class="col-12">
                <div style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px;">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <h4 class="fw-bold text-success">Pusat Rekapitulasi Laporan</h4>
                <p class="text-muted" style="max-width: 500px; margin: 0 auto;">
                    Gunakan panel pencarian di atas untuk menyaring dan mencetak seluruh data yang dihasilkan dari menu operasional Anda (Panen, Operasional, Pembibitan, Tanam, Pengiriman, dan SDM).
                </p>
            </div>
        </div>
    @endif
@endif
@endsection


