@extends('layouts.app')

@section('title', 'Upah Pekerja Kopi')

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

    /* Harvest Season Banner */
    .harvest-season-banner {
        background: linear-gradient(135deg, #3d2314 0%, #5c3a21 50%, #c9a054 100%);
        border-radius: 20px;
        padding: 30px;
        color: #ffffff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(92, 58, 33, 0.1);
    }

    .harvest-season-banner::after {
        content: '\f0c0';
        font-family: 'FontAwesome';
        position: absolute;
        right: 15px;
        bottom: -25px;
        font-size: 8.5rem;
        color: rgba(255, 255, 255, 0.04);
        pointer-events: none;
    }

    /* Metrics cards */
    .stat-card-kopi-sdm {
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(92, 58, 33, 0.02);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s;
    }

    .stat-card-kopi-sdm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(92, 58, 33, 0.05);
    }

    .stat-card-kopi-sdm .metric-lbl {
        font-size: 0.78rem;
        font-weight: 700;
        color: #8a7a71;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .stat-card-kopi-sdm .metric-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #3d2314;
        margin-top: 4px;
        font-family: 'Outfit', sans-serif;
    }

    /* Interactive Calculator Form */
    .calc-card-kopi-sdm {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }

    .salary-preview-box {
        background-color: #faf7f2;
        border: 1px dashed #e6ccb2;
        border-radius: 12px;
        padding: 16px;
        margin-top: 20px;
        text-align: center;
    }

    .salary-preview-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #8a7a71;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .salary-preview-val {
        font-size: 1.8rem;
        font-weight: 850;
        color: #3d2314;
        font-family: 'Outfit', sans-serif;
        margin: 5px 0;
    }

    /* Table styles */
    .table-coffee-sdm th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #8a7a71;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        background-color: #faf7f2;
    }

    .table-coffee-sdm td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }

    .worker-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e6ccb2;
    }

    .badge-job-role {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        text-transform: uppercase;
    }

    .badge-job-role.pemanen {
        background-color: #eef2f3;
        color: #3d2314;
        border: 1px solid #cbd5e1;
    }

    .badge-job-role.tukang-pupuk {
        background-color: #fef3c7;
        color: #92400e;
    }

    .badge-job-role.tukang-semprot {
        background-color: #ffe4e6;
        color: #9f1239;
    }

    /* Role selection helper buttons */
    .job-btn-group {
        display: flex;
        gap: 8px;
        margin-bottom: 15px;
    }

    .btn-job-choice {
        flex: 1;
        padding: 10px;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #7f6e62;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
    }

    .btn-job-choice:hover {
        background-color: #faf7f2;
        border-color: #e6ccb2;
        color: #3d2314;
    }

    .btn-job-choice.active {
        background-color: #5c3a21;
        color: #ffffff;
        border-color: #5c3a21;
    }
</style>
@endsection

@section('content')
<!-- Harvest Raya Banner -->
<div class="harvest-season-banner">
    <span class="text-uppercase font-monospace" style="font-size: 0.72rem; letter-spacing: 1.5px; opacity: 0.9;">Komoditas Kopi</span>
    <h2 class="fw-bold mt-1 mb-2" style="font-family: 'Outfit';">Masa Panen Raya Kebun</h2>
    <p class="m-0 opacity-90 max-w-xl" style="font-size: 0.92rem;">Pengaturan payroll upah buruh pemanen kopi, buruh penyemprot hama, dan pemupuk berkala untuk menjaga kualitas produksi ceri merah.</p>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <!-- Total Wages MTD -->
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="stat-card-kopi-sdm">
            <div>
                <span class="metric-lbl">Total Wages MTD</span>
                <div class="metric-val">Rp {{ number_format($totalWagesMtd, 0, ',', '.') }}</div>
            </div>
            <span class="badge bg-success-subtle text-success metric-badge">Bulan Ini</span>
        </div>
    </div>

    <!-- Pekerja Aktif -->
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="stat-card-kopi-sdm">
            <div>
                <span class="metric-lbl">Pekerja Aktif Kopi</span>
                <div class="metric-val">{{ $activeWorkersCount }} Orang</div>
            </div>
            <span class="badge bg-secondary-subtle text-secondary metric-badge">Terdaftar</span>
        </div>
    </div>

    <!-- Rata-rata Upah -->
    <div class="col-md-4">
        <div class="stat-card-kopi-sdm">
            <div>
                <span class="metric-lbl">Rata-Rata Tarif</span>
                <div class="metric-val">Rp 77.500 / hari</div>
            </div>
            <span class="badge bg-info-subtle text-info metric-badge">Standard</span>
        </div>
    </div>
</div>

<!-- Grid Form & Table -->
<div class="row mb-5">
    <!-- Left Column: Form & Salary Calculator -->
    <div class="col-lg-5 mb-4 mb-lg-0">
        <div class="calc-card-kopi-sdm h-100">
            <h5 class="fw-bold mb-3" style="color: #3d2314; font-family: 'Outfit';">Pencatatan & Kalkulator Upah</h5>
            <p class="text-muted" style="font-size: 0.85rem;">Input kehadiran hari kerja dan kalkulasi gaji buruh kopi secara otomatis.</p>
            
            <form method="POST" action="{{ route('sdm.store') }}" id="sdmKopiForm" class="mt-4">
                @csrf
                <!-- Dynamic hidden inputs -->
                <input type="hidden" name="jenis_pekerjaan" id="jenis_pekerjaan" value="Pemanen">

                <div class="mb-3">
                    <label for="nama_pekerja" class="form-label fw-semibold">Nama Pekerja</label>
                    <input type="text" name="nama_pekerja" id="nama_pekerja" class="form-control" placeholder="Contoh: Ahmad Sobari" required>
                </div>

                <!-- Job Role Selector Buttons -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Pekerjaan (Peran)</label>
                    <div class="job-btn-group">
                        <button type="button" class="btn-job-choice active" id="btnJobPemanen" onclick="selectJobRole('Pemanen', 80000)">
                            Pemanen
                        </button>
                        <button type="button" class="btn-job-choice" id="btnJobPupuk" onclick="selectJobRole('Tukang Pupuk', 75000)">
                            Tukang Pupuk
                        </button>
                        <button type="button" class="btn-job-choice" id="btnJobSemprot" onclick="selectJobRole('Tukang Semprot', 75000)">
                            Tukang Semprot
                        </button>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="upah_harian" class="form-label fw-semibold">Tarif Upah Harian (Rp)</label>
                        <input type="number" name="upah_harian" id="upah_harian" class="form-control" value="80000" oninput="calculateTotalGaji()" required>
                    </div>
                    <div class="col-6">
                        <label for="jumlah_hari" class="form-label fw-semibold">Hari Kerja (Kehadiran)</label>
                        <input type="number" name="jumlah_hari" id="jumlah_hari" class="form-control" placeholder="Contoh: 22" oninput="calculateTotalGaji()" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="tanggal_kerja" class="form-label fw-semibold">Tanggal Log Pembayaran</label>
                    <input type="date" name="tanggal_kerja" id="tanggal_kerja" class="form-control" required>
                </div>

                <!-- Calculator Live Preview -->
                <div class="salary-preview-box">
                    <div class="salary-preview-title">Estimasi Total Upah Diterima</div>
                    <div class="salary-preview-val" id="salaryVal">Rp 0</div>
                    <small class="text-muted" id="salaryFormula">0 hari x Rp 0 /hari</small>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success w-100 py-2" style="background-color: #3d2314; border-color: #3d2314; border-radius: 8px; font-weight: 600;">
                        Simpan Log Upah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Table Log Payouts -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <span class="fw-bold text-success" style="font-size: 1.05rem; font-family: 'Outfit';">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Rincian Upah Pekerja Terkini
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-coffee-sdm align-middle m-0" style="min-width: 600px;">
                        <thead>
                            <tr>
                                <th>NAMA PEKERJA</th>
                                <th>PERAN</th>
                                <th>TARIF</th>
                                <th>HARI</th>
                                <th>TOTAL UPAS</th>
                                <th class="text-center" style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPekerja as $pekerja)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @php
                                                // Generate randomized Unsplash avatar to match mockup premium design
                                                $seedVal = substr(md5($pekerja->nama_pekerja), 0, 5);
                                                $avatarUrl = 'https://api.dicebear.com/7.x/adventurer/svg?seed=' . $seedVal;
                                            @endphp
                                            <div class="block-code-avatar-circle" style="background-color: #faf7f2; border: 1px solid #e6ccb2; font-size: 0.85rem; width: 32px; height: 32px;">
                                                {{ substr($pekerja->nama_pekerja, 0, 2) }}
                                            </div>
                                            <div class="fw-bold text-dark">{{ $pekerja->nama_pekerja }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $jobClass = 'pemanen';
                                            if ($pekerja->jenis_pekerjaan == 'Tukang Pupuk') $jobClass = 'tukang-pupuk';
                                            if ($pekerja->jenis_pekerjaan == 'Tukang Semprot') $jobClass = 'tukang-semprot';
                                        @endphp
                                        <span class="badge-job-role {{ $jobClass }}">
                                            {{ $pekerja->jenis_pekerjaan }}
                                        </span>
                                    </td>
                                    <td class="text-muted">Rp {{ number_format($pekerja->upah_harian, 0, ',', '.') }}</td>
                                    <td class="fw-semibold text-center">{{ $pekerja->jumlah_hari }} hari</td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($pekerja->total_gaji, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('sdm.edit', $pekerja->id) }}"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit</a></li>
                                                <li>
                                                    <form action="{{ route('sdm.destroy', $pekerja->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-trash me-2"></i> Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                        <p class="m-0">Belum ada log data upah buruh kopi.</p>
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
        Menampilkan {{ $dataPekerja->firstItem() ?? 0 }}-{{ $dataPekerja->lastItem() ?? 0 }} dari total {{ $dataPekerja->total() }} data pekerja
    </div>
    <div>
        {{ $dataPekerja->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    let selectedRole = 'Pemanen';

    $(document).ready(function() {
        // Set default date picker value to today
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_kerja').val(today);

        // Run calculator once to initialize
        calculateTotalGaji();
    });

    function selectJobRole(roleName, defaultRate) {
        selectedRole = roleName;
        
        // Update hidden input
        $('#jenis_pekerjaan').val(roleName);

        // Update active class on selector buttons
        $('#btnJobPemanen, #btnJobPupuk, #btnJobSemprot').removeClass('active');
        if (roleName === 'Pemanen') $('#btnJobPemanen').addClass('active');
        else if (roleName === 'Tukang Pupuk') $('#btnJobPupuk').addClass('active');
        else if (roleName === 'Tukang Semprot') $('#btnJobSemprot').addClass('active');

        // Set default rate
        $('#upah_harian').val(defaultRate);

        // Recompute calculator
        calculateTotalGaji();
    }

    function calculateTotalGaji() {
        const rate = parseFloat($('#upah_harian').val()) || 0;
        const days = parseInt($('#jumlah_hari').val()) || 0;
        const total = rate * days;

        // Update preview widgets
        $('#salaryVal').text(formatRupiah(total));
        $('#salaryFormula').text(`${days} hari x Rp ${rate.toLocaleString('id-ID')} /hari`);
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }
</script>
@endsection
