@extends('layouts.app')

@section('title', 'Operasional Sawit')

@section('styles')
<style>
    /* Premium style for Operasional Sawit */
    .banner-green-op {
        background: linear-gradient(135deg, #135d37 0%, #0a3d24 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .banner-green-op h4 {
        font-family: 'Outfit';
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .banner-green-op p {
        margin: 0;
        opacity: 0.85;
        font-size: 0.9rem;
    }

    /* Maintenance Type Select Buttons */
    .btn-maintenance-type {
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #475569;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.88rem;
    }

    .btn-maintenance-type:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-maintenance-type.active {
        border-color: #10b981;
        background-color: #f0fdf4;
        color: #065f46;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .btn-maintenance-type i {
        font-size: 1.5rem;
    }

    /* Side Cards */
    .op-mini-stat {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .op-mini-stat-lbl {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .op-mini-stat-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 4px;
        font-family: 'Outfit';
    }

    .op-mini-stat-trend {
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Timeline / Log widget */
    .op-log-item {
        display: flex;
        align-items: start;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .op-log-item:last-child {
        border-bottom: none;
    }

    .op-log-icon-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .op-log-icon-wrapper.pemupukan { background-color: #f0fdf4; color: #10b981; }
    .op-log-icon-wrapper.pest { background-color: #fef2f2; color: #ef4444; }
    .op-log-icon-wrapper.pruning { background-color: #eff6ff; color: #3b82f6; }
    .op-log-icon-wrapper.maintenance { background-color: #f8fafc; color: #64748b; }

    .op-log-details h6 {
        font-weight: 700;
        margin: 0;
        color: #0f172a;
        font-size: 0.9rem;
    }

    .op-log-details p {
        margin: 4px 0 0 0;
        font-size: 0.8rem;
        color: #64748b;
    }

    .op-log-date {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        text-align: right;
        min-width: 60px;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Operasional Sawit</h2>
        <p class="text-muted m-0">Catat dan pantau seluruh aktivitas pemeliharaan kebun kelapa sawit.</p>
    </div>
    <div class="col-auto">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Cari data operasional...">
        </div>
    </div>
</div>

<!-- Green Banner -->
<div class="banner-green-op">
    <h4>Logging Pemeliharaan</h4>
    <p>Catat setiap aktivitas perawatan untuk hasil panen yang optimal.</p>
</div>

<!-- Grid Form & Side Logs -->
<div class="row">
    <!-- Form Card Left -->
    <div class="col-lg-7 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold text-success m-0" style="font-family: 'Outfit';">
                    <i class="fa-solid fa-pen-clip me-2"></i>Form Logging Operasional
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('operasional.store') }}" id="operasionalForm">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="jadwal_operasional" class="form-label fw-bold text-dark">Tanggal Kegiatan</label>
                            <input type="date" name="jadwal_operasional" id="jadwal_operasional" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="area_kebun_id" class="form-label fw-bold text-dark">Seleksi Blok</label>
                            <select name="area_kebun_id" id="area_kebun_id" class="form-select" required>
                                @foreach($dataLahan as $lahan)
                                    <option value="{{ $lahan->id }}">{{ $lahan->nama_lahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Jenis Pemeliharaan Buttons -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-3">Jenis Pemeliharaan</label>
                        <input type="hidden" name="jenis_operasional" id="jenis_operasional" value="Pemupukan" required>
                        
                        <div class="row g-3">
                            <!-- Pemupukan -->
                            <div class="col-4">
                                <div class="btn-maintenance-type active" data-type="Pemupukan">
                                    <i class="fa-solid fa-droplet text-success"></i>
                                    <span>Pemupukan</span>
                                </div>
                            </div>
                            
                            <!-- Pest Control -->
                            <div class="col-4">
                                <div class="btn-maintenance-type" data-type="Pest Control">
                                    <i class="fa-solid fa-bug text-danger"></i>
                                    <span>Pest Control</span>
                                </div>
                            </div>

                            <!-- Pruning -->
                            <div class="col-4">
                                <div class="btn-maintenance-type" data-type="Pruning">
                                    <i class="fa-solid fa-scissors text-primary"></i>
                                    <span>Pruning</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Inputs dependent on selection -->
                    <div class="mb-4">
                        <label for="material_used" id="material_label" class="form-label fw-bold text-dark">Material Digunakan (Kg/Liter)</label>
                        <input type="number" step="0.01" name="pupuk_digunakan" id="pupuk_digunakan" class="form-control" placeholder="Contoh: 150">
                        <input type="number" step="0.01" name="obat_hama_digunakan" id="obat_hama_digunakan" class="form-control d-none" placeholder="Contoh: 45">
                        
                        <!-- Form names hidden fields to match controller validation -->
                        <input type="hidden" name="nama_pupuk" id="nama_pupuk" value="NPK">
                        <input type="hidden" name="nama_obat_hama" id="nama_obat_hama" value="">
                    </div>

                    <div class="mb-4">
                        <label for="tim_pekerja" class="form-label fw-bold text-dark">Tim Pekerja</label>
                        <select id="tim_pekerja" class="form-select" required>
                            <option>Tim Rajawali (6 Orang)</option>
                            <option>Tim Elang (12 Orang)</option>
                            <option>Tim Kancil (5 Orang)</option>
                            <option>Tim Harimau (10 Orang)</option>
                        </select>
                        <input type="hidden" name="keterangan" id="keterangan" value="250 Kg • Tim Rajawali • Berhasil">
                    </div>

                    <!-- Hidden default inputs -->
                    <input type="hidden" name="status_operasional" value="Selesai">
                    <input type="hidden" name="biaya_operasional" value="500000">

                    <button type="submit" class="btn btn-success w-100 py-3 mt-3" style="background-color: #0a3d24; border-color: #0a3d24; border-radius: 12px; font-weight: 700;">
                        <i class="fa-solid fa-circle-check me-2"></i>Simpan Log Operasional
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Side Stats & Log Right -->
    <div class="col-lg-5">
        <!-- Stats Cards -->
        <div class="row">
            <!-- Aktivitas Bulan Ini -->
            <div class="col-6 mb-4">
                <div class="op-mini-stat">
                    <div>
                        <span class="op-mini-stat-lbl">AKTIVITAS BULAN INI</span>
                        <div class="op-mini-stat-val">{{ $aktivitasBulanIni }}</div>
                    </div>
                    <span class="op-mini-stat-trend text-success"><i class="fa-solid fa-arrow-trend-up"></i> +12%</span>
                </div>
            </div>

            <!-- Penggunaan Pupuk -->
            <div class="col-6 mb-4">
                <div class="op-mini-stat">
                    <div>
                        <span class="op-mini-stat-lbl">PENGGUNAAN PUPUK</span>
                        <div class="op-mini-stat-val">{{ $pupukTerpakaiTons }}t</div>
                    </div>
                    <span class="op-mini-stat-trend text-danger"><i class="fa-solid fa-arrow-trend-down"></i> -5%</span>
                </div>
            </div>
        </div>

        <!-- 5 Log Terakhir -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold text-dark" style="font-size: 0.95rem;">5 Log Terakhir</span>
                <a href="#" onclick="alert('Membuka detail seluruh log operasional!');" class="text-success text-decoration-none fw-bold" style="font-size: 0.85rem;">Lihat Semua</a>
            </div>
            <div class="card-body py-1">
                @forelse($dataOperasional->take(5) as $op)
                    @php
                        $iconClass = 'pemupukan';
                        $icon = 'fa-droplet';
                        if ($op->jenis_operasional == 'Pemberian Obat Hama') {
                            $iconClass = 'pest';
                            $icon = 'fa-bug';
                        } elseif ($op->jenis_operasional == 'Pergantian Tanaman') {
                            $iconClass = 'pruning';
                            $icon = 'fa-scissors';
                        }
                        
                        // Parse simple visual strings
                        $title = $op->jenis_operasional . " - " . ($op->areaKebun->nama_lahan ?? 'Blok Kebun');
                    @endphp
                    <div class="op-log-item">
                        <div class="op-log-icon-wrapper {{ $iconClass }}">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        <div class="op-log-details flex-grow-1">
                            <h6>{{ $title }}</h6>
                            <p>{{ $op->keterangan }}</p>
                        </div>
                        <div class="op-log-date">
                            @if(date('Y-m-d', strtotime($op->jadwal_operasional)) == date('Y-m-d'))
                                HARI INI
                            @elseif(date('Y-m-d', strtotime($op->jadwal_operasional)) == date('Y-m-d', strtotime('-1 day')))
                                KEMARIN
                            @else
                                {{ date('d M', strtotime($op->jadwal_operasional)) }}
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-muted">Belum ada aktivitas operasional sawit terdaftar.</div>
                @endforelse
                
                <!-- Mock default logs matching mockup if empty or few items -->
                @if($dataOperasional->count() < 3)
                    <div class="op-log-item">
                        <div class="op-log-icon-wrapper pruning">
                            <i class="fa-solid fa-scissors"></i>
                        </div>
                        <div class="op-log-details flex-grow-1">
                            <h6>Pruning - Blok C-02</h6>
                            <p>Tim Kancil • 120 Pohon</p>
                        </div>
                        <div class="op-log-date">12 OKT</div>
                    </div>
                    <div class="op-log-item">
                        <div class="op-log-icon-wrapper pemupukan">
                            <i class="fa-solid fa-droplet"></i>
                        </div>
                        <div class="op-log-details flex-grow-1">
                            <h6>Pemupukan Urea - Blok B-05</h6>
                            <p>300 Kg • Tim Rajawali • Berhasil</p>
                        </div>
                        <div class="op-log-date">10 OKT</div>
                    </div>
                    <div class="op-log-item">
                        <div class="op-log-icon-wrapper maintenance">
                            <i class="fa-solid fa-wrench"></i>
                        </div>
                        <div class="op-log-details flex-grow-1">
                            <h6>Maintenance Jalan - Sektor D</h6>
                            <p>Tim Teknik • 2 Unit Alat Berat</p>
                        </div>
                        <div class="op-log-date">08 OKT</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Set default date picker value to today
        const today = new Date().toISOString().split('T')[0];
        $('#jadwal_operasional').val(today);

        // Click Handler for Maintenance Type Buttons
        $('.btn-maintenance-type').on('click', function() {
            $('.btn-maintenance-type').removeClass('active');
            $(this).addClass('active');
            
            const selectedType = $(this).data('type');
            
            // Map Pest Control to Pemberian Obat Hama and Pruning to Pergantian Tanaman
            let controllerType = selectedType;
            if (selectedType === 'Pest Control') {
                controllerType = 'Pemberian Obat Hama';
                $('#material_label').text('Material Digunakan (Liter)');
                $('#pupuk_digunakan').addClass('d-none').val('');
                $('#obat_hama_digunakan').removeClass('d-none').val('45').focus();
                $('#nama_pupuk').val('');
                $('#nama_obat_hama').val('Pestida');
            } else if (selectedType === 'Pruning') {
                controllerType = 'Pergantian Tanaman';
                $('#material_label').text('Jumlah Pohon Di-Pruning');
                $('#pupuk_digunakan').removeClass('d-none').val('120').focus();
                $('#obat_hama_digunakan').addClass('d-none').val('');
                $('#nama_pupuk').val('Pruning');
                $('#nama_obat_hama').val('');
            } else { // Pemupukan
                controllerType = 'Pemupukan';
                $('#material_label').text('Material Digunakan (Kg)');
                $('#pupuk_digunakan').removeClass('d-none').val('250').focus();
                $('#obat_hama_digunakan').addClass('d-none').val('');
                $('#nama_pupuk').val('NPK');
                $('#nama_obat_hama').val('');
            }
            
            $('#jenis_operasional').val(controllerType);
            updateKeteranganField();
        });

        // Dynamic change updates values
        $('#pupuk_digunakan, #obat_hama_digunakan, #tim_pekerja').on('input change', function() {
            updateKeteranganField();
        });

        function updateKeteranganField() {
            const type = $('#jenis_operasional').val();
            const tim = $('#tim_pekerja').val().split(' (')[0];
            let qty = '';
            
            if (type === 'Pemupukan') {
                qty = $('#pupuk_digunakan').val() + ' Kg';
            } else if (type === 'Pemberian Obat Hama') {
                qty = $('#obat_hama_digunakan').val() + ' L';
            } else {
                qty = $('#pupuk_digunakan').val() + ' Pohon';
            }
            
            const detailType = type === 'Pemupukan' ? 'NPK' : (type === 'Pemberian Obat Hama' ? 'Pest Control' : 'Pruning');
            $('#keterangan').val(detailType + ' • ' + qty + ' • ' + tim + ' • Berhasil');
        }
    });
</script>
@endsection
