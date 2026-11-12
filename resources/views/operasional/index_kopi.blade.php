@extends('layouts.app')

@section('title', 'Operasional Pemeliharaan Kopi')

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

    /* Overrides */
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

    /* Weather widget */
    .weather-widget-kopi {
        background: linear-gradient(135deg, #5c3a21 0%, #3d2314 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .weather-widget-kopi::after {
        content: '\f185';
        font-family: 'FontAwesome';
        position: absolute;
        right: 10px;
        bottom: -15px;
        font-size: 6rem;
        color: rgba(255, 255, 255, 0.05);
        pointer-events: none;
    }

    /* Interactive toggle buttons for operasional types */
    .op-toggle-group {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn-op-toggle {
        padding: 12px;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #7f6e62;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-op-toggle:hover {
        background-color: #faf7f2;
        color: #3d2314;
        border-color: #e6ccb2;
    }

    .btn-op-toggle.active {
        background-color: #5c3a21;
        color: #ffffff;
        border-color: #5c3a21;
        box-shadow: 0 4px 10px rgba(92, 58, 33, 0.15);
    }

    /* Form and schedule styling */
    .form-card-kopi-op {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }

    .schedule-card-kopi {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
    }

    .schedule-item-kopi {
        padding: 12px;
        border-radius: 10px;
        background-color: #faf7f2;
        margin-bottom: 12px;
        border-left: 4px solid #c9a054;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Table styles */
    .table-coffee-op th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #8a7a71;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        background-color: #faf7f2;
    }

    .table-coffee-op td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }

    .badge-status-op {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 30px;
        text-transform: uppercase;
    }

    .badge-status-op.selesai {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-status-op.proses {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-status-op.jadwal {
        background-color: #f1f5f9;
        color: #475569;
    }

    /* Dynamic inputs container */
    .dynamic-input-group {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Operasional Pemeliharaan Kopi</h2>
        <p class="text-muted m-0">Catat dan kelola jadwal pemupukan, pest control, pruning tunas air, dan replanting pohon kopi.</p>
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-secondary" onclick="alert('Laporan operasional kopi berhasil diunduh!');" style="padding: 8px 16px; border-color: #e6ccb2; color: #3d2314;">
            <i class="fa-solid fa-file-pdf me-1"></i> Cetak Operasional
        </button>
    </div>
</div>

<!-- Left Column: Form & Weather / Right Column: Schedule -->
<div class="row mb-5">
    <!-- Form and Weather Column -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <!-- Weather and Farm Health Alert Widget -->
        <div class="weather-widget-kopi">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <span class="text-uppercase font-monospace" style="font-size: 0.72rem; letter-spacing: 1px; color: #e6ccb2;">Kondisi Cuaca & Kebun</span>
                    <h3 class="fw-bold mt-1 mb-2" style="font-family: 'Outfit';">Cianjur, Jawa Barat</h3>
                    <p class="m-0 opacity-90" style="font-size: 0.9rem;">Suhu rata-rata <strong>24°C</strong>. Udara sejuk pegunungan dengan kelembaban optimal <strong>65%</strong>. Sangat mendukung pengerjaan <strong>Pruning & Pemupukan</strong> hari ini.</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <div style="font-size: 3rem; font-weight: 800; font-family: 'Outfit'; line-height: 1;">24°C</div>
                    <div class="fw-bold mt-1" style="color: #c9a054;"><i class="fa-solid fa-cloud-sun me-1"></i> Cerah Berawan</div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card-kopi-op">
            <h5 class="fw-bold mb-3" style="color: #3d2314; font-family: 'Outfit';">Catat Log Kegiatan Baru</h5>
            
            <form method="POST" action="{{ route('operasional.store') }}" enctype="multipart/form-data" id="kopiOpForm">
                @csrf
                <!-- Dynamic hidden inputs -->
                <input type="hidden" name="jenis_operasional" id="jenis_operasional" value="Pemupukan">
                <!-- Set fallback values for safe inserts -->
                <input type="hidden" name="pupuk_digunakan" id="pupuk_digunakan" value="0.00">
                <input type="hidden" name="obat_hama_digunakan" id="obat_hama_digunakan" value="0.00">
                <input type="hidden" name="bibit_baru" id="bibit_baru" value="0">
                <input type="hidden" name="pergantian_tanaman" id="pergantian_tanaman" value="0">

                <!-- 1. Selection Toggles -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Jenis Kegiatan Lahan</label>
                    <div class="op-toggle-group">
                        <button type="button" class="btn-op-toggle active" id="btnPemupukan" onclick="selectOpType('Pemupukan')">
                            Pemupukan
                        </button>
                        <button type="button" class="btn-op-toggle" id="btnPest" onclick="selectOpType('Pemberian Obat Hama')">
                            Pest Control
                        </button>
                        <button type="button" class="btn-op-toggle" id="btnPruning" onclick="selectOpType('Pembibitan')">
                            Pruning
                        </button>
                        <button type="button" class="btn-op-toggle" id="btnReplant" onclick="selectOpType('Pergantian Tanaman')">
                            Replanting
                        </button>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="area_kebun_id" class="form-label fw-semibold">Blok Lahan Kopi</label>
                        <select name="area_kebun_id" id="area_kebun_id" class="form-select" required>
                            @foreach($dataLahan as $lahan)
                                <option value="{{ $lahan->id }}">{{ $lahan->nama_lahan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jadwal_operasional" class="form-label fw-semibold">Tanggal Kegiatan</label>
                        <input type="date" name="jadwal_operasional" id="jadwal_operasional" class="form-control" required>
                    </div>
                </div>

                <!-- 2. Dynamic Input Fields based on toggles -->
                <!-- A. Pemupukan inputs -->
                <div id="groupPemupukan" class="dynamic-input-group mb-3" style="display: block;">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="nama_pupuk" class="form-label fw-semibold">Nama Pupuk</label>
                            <input type="text" name="nama_pupuk" id="nama_pupuk" class="form-control" placeholder="Contoh: NPK Organik Phonska">
                        </div>
                        <div class="col-md-6">
                            <label for="input_pupuk_qty" class="form-label fw-semibold">Jumlah Pupuk Terpakai (KG)</label>
                            <input type="number" step="0.01" id="input_pupuk_qty" class="form-control" placeholder="0.00" oninput="syncQty('pupuk')">
                        </div>
                    </div>
                </div>

                <!-- B. Pest Control inputs -->
                <div id="groupPest" class="dynamic-input-group mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="nama_obat_hama" class="form-label fw-semibold">Nama Pestisida / Obat</label>
                            <input type="text" name="nama_obat_hama" id="nama_obat_hama" class="form-control" placeholder="Contoh: Fungisida Antracol 70WP">
                        </div>
                        <div class="col-md-6">
                            <label for="input_pest_qty" class="form-label fw-semibold">Volume Digunakan (Liter)</label>
                            <input type="number" step="0.01" id="input_pest_qty" class="form-control" placeholder="0.00" oninput="syncQty('pest')">
                        </div>
                    </div>
                </div>

                <!-- C. Pruning inputs -->
                <div id="groupPruning" class="dynamic-input-group mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="jenis_bibit" class="form-label fw-semibold">Jenis / Keterangan Tunas</label>
                            <input type="text" name="jenis_bibit" id="jenis_bibit" class="form-control" placeholder="Contoh: Pembuangan Tunas Air Cacing">
                        </div>
                        <div class="col-md-6">
                            <label for="input_bibit_qty" class="form-label fw-semibold">Jumlah Pohon Dipangkas</label>
                            <input type="number" id="input_bibit_qty" class="form-control" placeholder="0" oninput="syncQty('bibit')">
                        </div>
                    </div>
                </div>

                <!-- D. Replanting inputs -->
                <div id="groupReplant" class="dynamic-input-group mb-3">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="replant_jenis_bibit" class="form-label fw-semibold">Jenis Varietas Bibit</label>
                            <input type="text" id="replant_jenis_bibit" class="form-control" placeholder="Contoh: Robusta BP436">
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="jumlah_tanaman_mati" class="form-label fw-semibold">Jumlah Tanaman Mati</label>
                            <input type="number" name="jumlah_tanaman_mati" id="jumlah_tanaman_mati" class="form-control" placeholder="0" oninput="syncReplant()">
                        </div>
                        <div class="col-md-4">
                            <label for="jumlah_tanaman_diganti" class="form-label fw-semibold">Jumlah Tanaman Diganti</label>
                            <input type="number" name="jumlah_tanaman_diganti" id="jumlah_tanaman_diganti" class="form-control" placeholder="0" oninput="syncReplant()">
                        </div>
                    </div>
                </div>

                <!-- 3. General inputs -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="status_operasional" class="form-label fw-semibold">Status Tugas</label>
                        <select name="status_operasional" id="status_operasional" class="form-select" required>
                            <option value="Selesai">Selesai</option>
                            <option value="Proses">Sedang Berjalan</option>
                            <option value="Jadwal">Jadwal Mendatang</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="biaya_operasional" class="form-label fw-semibold">Biaya Kegiatan (Rp)</label>
                        <input type="number" name="biaya_operasional" id="biaya_operasional" class="form-control" placeholder="Contoh: 1500000" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label fw-semibold">Catatan / Deskripsi Kerja</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Tulis rincian penugasan atau catatan khusus lahan..."></textarea>
                </div>

                <div class="mb-4">
                    <label for="bukti_nota" class="form-label fw-semibold">Bukti Nota Pembelian / Foto Kerja</label>
                    <input type="file" name="bukti_nota" id="bukti_nota" class="form-control">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4 py-2" style="background-color: #3d2314; border-color: #3d2314; border-radius: 8px; font-weight: 600;">
                        Catat Log Pemeliharaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Scheduled tasks -->
    <div class="col-lg-4">
        <div class="schedule-card-kopi">
            <h5 class="fw-bold mb-3" style="color: #3d2314; font-family: 'Outfit';"><i class="fa-solid fa-calendar-days text-warning me-1"></i> Jadwal Mendatang</h5>
            <p class="text-muted" style="font-size: 0.85rem;">Daftar pemeliharaan kebun kopi yang sudah terjadwal.</p>
            
            <div class="mt-4">
                <!-- Item 1 -->
                <div class="schedule-item-kopi">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">Pemupukan Kopi</div>
                        <small class="text-muted"><i class="fa-solid fa-map-pin me-1"></i> Blok B-03 Lereng</small>
                    </div>
                    <span class="badge bg-warning text-dark font-monospace" style="font-size: 0.7rem;">25 JUN</span>
                </div>

                <!-- Item 2 -->
                <div class="schedule-item-kopi" style="border-left-color: #5c3a21;">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">Pruning Tunas Air</div>
                        <small class="text-muted"><i class="fa-solid fa-map-pin me-1"></i> Blok C-02 Barat</small>
                    </div>
                    <span class="badge bg-secondary font-monospace" style="font-size: 0.7rem; color: #fff;">28 JUN</span>
                </div>

                <!-- Item 3 -->
                <div class="schedule-item-kopi" style="border-left-color: #ae2012;">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">Inspeksi Karat Daun</div>
                        <small class="text-muted"><i class="fa-solid fa-map-pin me-1"></i> Blok A-01 Utama</small>
                    </div>
                    <span class="badge bg-danger font-monospace" style="font-size: 0.7rem; color: #fff;">30 JUN</span>
                </div>
            </div>

            <!-- Material Card Info -->
            <div class="mt-4 p-3 rounded-3" style="background-color: #faf7f2; border: 1px solid #e6ccb2;">
                <span class="text-muted font-monospace" style="font-size: 0.7rem; letter-spacing: 0.5px;">ESTIMASI MATERIAL BULAN INI</span>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">Pestisida / Obat Terpakai</span>
                    <span class="fw-bold text-success" style="font-size: 0.9rem;">{{ $materialTerpakaiLtr }} Liter</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">Total Aktivitas Kerja</span>
                    <span class="fw-bold text-success" style="font-size: 0.9rem;">{{ $aktivitasBulanIni }} Task</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Log Operasional Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <span class="section-title">
                    <i class="fa-solid fa-list-check me-2"></i>Riwayat Operasional Pemeliharaan Kopi
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-coffee-op align-middle m-0" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th>TANGGAL</th>
                                <th>BLOK LAHAN</th>
                                <th>KEGIATAN</th>
                                <th>DETAIL BAHAN / BIBIT</th>
                                <th>QTY</th>
                                <th>BIAYA OPERASIONAL</th>
                                <th>STATUS</th>
                                <th>PELAKSANA</th>
                                <th class="text-center" style="width: 50px;">NOTA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataOperasional as $op)
                                <tr>
                                    <td>{{ date('d M Y', strtotime($op->jadwal_operasional)) }}</td>
                                    <td class="fw-bold">{{ $op->areaKebun->nama_lahan ?? 'Blok Kebun' }}</td>
                                    <td class="fw-bold text-dark">
                                        {{ $op->jenis_operasional == 'Pemberian Obat Hama' ? 'Pest Control' : ($op->jenis_operasional == 'Pembibitan' ? 'Pruning' : ($op->jenis_operasional == 'Pergantian Tanaman' ? 'Replanting' : $op->jenis_operasional)) }}
                                    </td>
                                    <td>
                                        @if($op->jenis_operasional == 'Pemupukan')
                                            <span class="text-muted"><i class="fa-solid fa-cubes me-1"></i> {{ $op->nama_pupuk }}</span>
                                        @elseif($op->jenis_operasional == 'Pemberian Obat Hama')
                                            <span class="text-muted"><i class="fa-solid fa-flask-vial me-1"></i> {{ $op->nama_obat_hama }}</span>
                                        @elseif($op->jenis_operasional == 'Pergantian Tanaman')
                                            <span class="text-muted"><i class="fa-solid fa-leaf me-1"></i> {{ $op->jenis_bibit ?: 'Robusta BP436' }}</span>
                                        @else
                                            <span class="text-muted"><i class="fa-solid fa-scissors me-1"></i> {{ $op->jenis_bibit ?: 'Tunas Air' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($op->jenis_operasional == 'Pemupukan')
                                            {{ number_format($op->pupuk_digunakan, 0) }} kg
                                        @elseif($op->jenis_operasional == 'Pemberian Obat Hama')
                                            {{ number_format($op->obat_hama_digunakan, 0) }} L
                                        @elseif($op->jenis_operasional == 'Pergantian Tanaman')
                                            {{ $op->bibit_baru }} Bibit
                                        @else
                                            {{ $op->bibit_baru ?: 150 }} Pangkas
                                        @endif
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        Rp {{ number_format($op->biaya_operasional, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge-status-op {{ strtolower($op->status_operasional) }}">
                                            {{ $op->status_operasional == 'Selesai' ? 'Selesai' : ($op->status_operasional == 'Proses' ? 'Berjalan' : 'Jadwal') }}
                                        </span>
                                    </td>
                                    <td>{{ $op->user->name ?? 'Pengurus' }}</td>
                                    <td class="text-center">
                                        @if($op->bukti_nota)
                                            <a href="{{ asset('storage/' . $op->bukti_nota) }}" target="_blank" class="text-success"><i class="fa-solid fa-receipt fs-5"></i></a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                        <p class="m-0">Belum ada riwayat pencatatan operasional kopi yang masuk.</p>
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
        Menampilkan {{ $dataOperasional->firstItem() ?? 0 }}-{{ $dataOperasional->lastItem() ?? 0 }} dari total {{ $dataOperasional->total() }} data operasional
    </div>
    <div>
        {{ $dataOperasional->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Set default date picker value to today
        const today = new Date().toISOString().split('T')[0];
        $('#jadwal_operasional').val(today);
    });

    function selectOpType(type) {
        // Update hidden input
        $('#jenis_operasional').val(type);

        // Update toggles active class
        $('#btnPemupukan, #btnPest, #btnPruning, #btnReplant').removeClass('active');
        if (type === 'Pemupukan') $('#btnPemupukan').addClass('active');
        else if (type === 'Pemberian Obat Hama') $('#btnPest').addClass('active');
        else if (type === 'Pembibitan') $('#btnPruning').addClass('active');
        else if (type === 'Pergantian Tanaman') $('#btnReplant').addClass('active');

        // Toggle form input groups
        $('.dynamic-input-group').hide();
        if (type === 'Pemupukan') {
            $('#groupPemupukan').show();
        } else if (type === 'Pemberian Obat Hama') {
            $('#groupPest').show();
        } else if (type === 'Pembibitan') {
            $('#groupPruning').show();
        } else if (type === 'Pergantian Tanaman') {
            $('#groupReplant').show();
        }
    }

    // Sync input values to matching hidden backend model columns
    function syncQty(type) {
        if (type === 'pupuk') {
            const val = parseFloat($('#input_pupuk_qty').val()) || 0;
            $('#pupuk_digunakan').val(val);
        } else if (type === 'pest') {
            const val = parseFloat($('#input_pest_qty').val()) || 0;
            $('#obat_hama_digunakan').val(val);
        } else if (type === 'bibit') {
            const val = parseInt($('#input_bibit_qty').val()) || 0;
            $('#bibit_baru').val(val);
        }
    }

    function syncReplant() {
        const valMati = parseInt($('#jumlah_tanaman_mati').val()) || 0;
        const valGanti = parseInt($('#jumlah_tanaman_diganti').val()) || 0;
        const replantSeedName = $('#replant_jenis_bibit').val() || 'Robusta BP436';
        
        $('#pergantian_tanaman').val(valGanti);
        $('#jenis_bibit').val(replantSeedName);
        $('#bibit_baru').val(valGanti);
    }
</script>
@endsection
