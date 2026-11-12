@extends('layouts.app')

@section('title', 'Upah Pekerja Lada')

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

    /* Active navigation indicator */
    .sidebar-link.active {
        color: #065f46 !important;
        background-color: #d1fae5 !important;
        border-left: 4px solid #10b981 !important;
    }

    .btn-success {
        background-color: #065f46 !important;
        border-color: #065f46 !important;
    }
    .btn-success:hover {
        background-color: #047857 !important;
        border-color: #047857 !important;
    }

    .text-success {
        color: #065f46 !important;
    }

    /* Metric cards styling */
    .stat-card-lada-sdm {
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(6, 95, 70, 0.02);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s;
    }

    .stat-card-lada-sdm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(6, 95, 70, 0.05);
    }

    .stat-card-lada-sdm .metric-lbl {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .stat-card-lada-sdm .metric-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #065f46;
        margin-top: 4px;
        font-family: 'Outfit', sans-serif;
    }

    .stat-card-lada-sdm .metric-sub {
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 4px;
        color: #64748b;
    }

    /* Calculator & Form styling */
    .form-card-upah-lada {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        height: 100%;
    }

    .radio-job-option {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.2s;
    }

    .radio-job-option:hover {
        background-color: #f8fafc;
        border-color: #94a3b8;
    }

    .radio-job-option.active {
        background-color: #f0fdf4;
        border-color: #10b981;
    }

    .radio-job-option input[type="radio"] {
        margin-right: 12px;
        accent-color: #065f46;
    }

    .salary-estimate-box {
        background-color: #f0fdf4;
        border: 1px dashed #10b981;
        border-radius: 12px;
        padding: 16px;
        margin-top: 20px;
        text-align: center;
    }

    .salary-estimate-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #047857;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .salary-estimate-val {
        font-size: 1.8rem;
        font-weight: 850;
        color: #065f46;
        font-family: 'Outfit', sans-serif;
        margin: 5px 0;
    }

    /* Table custom designs */
    .table-lada-sdm th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #475569;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        background-color: #f8fafc;
    }

    .table-lada-sdm td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.88rem;
    }

    .table-lada-sdm tr:hover {
        background-color: #f8fafc;
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

    .badge-job {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        text-transform: uppercase;
    }

    .badge-job.panen-lada {
        background-color: #eef2f3;
        color: #0f172a;
        border: 1px solid #cbd5e1;
    }

    .badge-job.pemupukan-rutin {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .badge-job.pembersihan-lahan {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    /* Side standard tariff note card */
    .note-card-lada {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
    }

    .note-card-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Upah Pekerja Lada</h2>
        <p class="text-muted m-0">Catat durasi kerja harian buruh dan monitor anggaran payroll upah lada.</p>
    </div>
    <div class="col-auto">
        <form method="GET" action="{{ route('sdm.index') }}" class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" name="cari" class="form-control border-start-0" placeholder="Cari pekerja..." value="{{ request('cari') }}">
        </form>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <!-- Total Wages -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-lada-sdm">
            <div>
                <span class="metric-lbl">Total Upah (MTD)</span>
                <div class="metric-val">Rp {{ number_format($totalWagesMtd, 0, ',', '.') }}</div>
                <div class="metric-sub text-success">
                    <i class="fa-solid fa-arrow-trend-up"></i> +8.4% vs last month
                </div>
            </div>
            <div class="fs-2 text-success opacity-25"><i class="fa-solid fa-wallet"></i></div>
        </div>
    </div>

    <!-- Pekerja Aktif -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-lada-sdm">
            <div>
                <span class="metric-lbl">Pekerja Aktif</span>
                <div class="metric-val">{{ $activeWorkersCount }} Orang</div>
                <div class="metric-sub text-muted">Kehadiran 92% hari ini</div>
            </div>
            <div class="fs-2 text-secondary opacity-25"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>

    <!-- Menunggu Verifikasi -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-lada-sdm">
            <div>
                <span class="metric-lbl">Menunggu Verifikasi</span>
                <div class="metric-val text-warning">{{ $waitingVerification }} Laporan</div>
                <div class="metric-sub text-warning">Segera verifikasi</div>
            </div>
            <div class="fs-2 text-warning opacity-25"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>
    </div>

    <!-- Efisiensi Biaya -->
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-lada-sdm">
            <div>
                <span class="metric-lbl">Efisiensi Biaya</span>
                <div class="metric-val">{{ $costEfficiency }}%</div>
                <div class="metric-sub text-success">Target 80% Tercapai</div>
            </div>
            <div class="fs-2 text-success opacity-25"><i class="fa-solid fa-chart-line"></i></div>
        </div>
    </div>
</div>

<!-- Grid Form & Table -->
<div class="row mb-5">
    <!-- Left Column: Form Catat Upah -->
    <div class="col-lg-5 mb-4 mb-lg-0">
        <div class="form-card-upah-lada">
            <h5 class="fw-bold text-success mb-4" style="font-family: 'Outfit';">
                <i class="fa-solid fa-file-signature me-2"></i>Catat Upah Baru
            </h5>

            <form method="POST" action="{{ route('sdm.store') }}" id="lada-upah-form">
                @csrf
                
                <div class="mb-3">
                    <label for="nama_pekerja" class="form-label fw-bold" style="font-size: 0.85rem;">Nama Pekerja</label>
                    <select name="nama_pekerja" id="nama_pekerja" class="form-select" required>
                        <option value="" disabled selected>Pilih Nama Pekerja...</option>
                        <option value="Asep Sunandar">Asep Sunandar</option>
                        <option value="Bambang Wijaya">Bambang Wijaya</option>
                        <option value="Cecep Rahman">Cecep Rahman</option>
                        <option value="Dedi Suhendar">Dedi Suhendar</option>
                        <option value="Eko Prasetyo">Eko Prasetyo</option>
                        <option value="Feri Setiawan">Feri Setiawan</option>
                        <option value="Heri Santoso">Heri Santoso</option>
                        <option value="Joko Susilo">Joko Susilo</option>
                        <option value="M. Ridwan">M. Ridwan</option>
                        <option value="Slamet Riyadi">Slamet Riyadi</option>
                    </select>
                </div>

                <!-- Jenis Pekerjaan Radios -->
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Jenis Pekerjaan</label>
                    
                    <div class="radio-job-option active" id="option-panen" onclick="setJob('Panen Lada', 150000)">
                        <input type="radio" name="jenis_pekerjaan" value="Panen Lada" checked required>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.88rem;">Panen Lada</div>
                            <small class="text-muted" style="font-size: 0.78rem;">Rate Standar: Rp 150.000 / Hari</small>
                        </div>
                    </div>

                    <div class="radio-job-option" id="option-pupuk" onclick="setJob('Pemupukan Rutin', 135000)">
                        <input type="radio" name="jenis_pekerjaan" value="Pemupukan Rutin">
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.88rem;">Pemupukan Rutin</div>
                            <small class="text-muted" style="font-size: 0.78rem;">Rate Standar: Rp 135.000 / Hari</small>
                        </div>
                    </div>

                    <div class="radio-job-option" id="option-pembersihan" onclick="setJob('Pembersihan Lahan', 120000)">
                        <input type="radio" name="jenis_pekerjaan" value="Pembersihan Lahan">
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.88rem;">Pembersihan Lahan</div>
                            <small class="text-muted" style="font-size: 0.78rem;">Rate Standar: Rp 120.000 / Hari</small>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Durasi Kerja (Jumlah Hari) -->
                    <div class="col-6">
                        <label for="jumlah_hari" class="form-label fw-bold" style="font-size: 0.85rem;">Durasi Kerja (Hari)</label>
                        <input type="number" name="jumlah_hari" id="jumlah_hari" class="form-control" value="1" min="1" required>
                    </div>
                    <!-- Rate Upah -->
                    <div class="col-6">
                        <label for="upah_harian" class="form-label fw-bold" style="font-size: 0.85rem;">Rate Upah (Rp/Hari)</label>
                        <input type="number" name="upah_harian" id="upah_harian" class="form-control" value="150000" min="1" required>
                    </div>
                </div>

                <!-- Hidden inputs for default Laravel SDM compatibility -->
                <input type="hidden" name="tanggal_kerja" id="tanggal_kerja" value="{{ date('Y-m-d') }}">

                <!-- Salary Live Preview Box -->
                <div class="salary-estimate-box">
                    <div class="salary-estimate-title">Estimasi Total Pembayaran</div>
                    <div class="salary-estimate-val" id="salary-preview">Rp 150.000</div>
                    <small class="text-muted" id="salary-formula">1 hari x Rp 150.000 / hari</small>
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 mt-4" style="border-radius: 12px; font-weight: 700;">
                    <i class="fa-solid fa-plus me-1"></i> Simpan Catatan Upah
                </button>
            </form>
        </div>

        <!-- Note Card standard tariff -->
        <div class="note-card-lada">
            <div class="note-card-title text-success">
                <i class="fa-solid fa-circle-info"></i> Standar Upah Lada
            </div>
            <p class="m-0 text-muted" style="font-size: 0.78rem; line-height: 1.4;">
                Tarif upah harian disesuaikan dengan tingkat kesulitan pekerjaan dan area kemiringan blok perkebunan lada. Pembersihan tebing lereng mendapat tambahan premi makan siang.
            </p>
        </div>
    </div>

    <!-- Right Column: Daftar Pembayaran Upah -->
    <div class="col-lg-7">
        <div class="card h-100 mb-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-clock-rotate-left me-2 text-success"></i>Daftar Pembayaran Upah
                </span>
                <span class="badge bg-success-subtle text-success rounded-pill">Total: {{ $dataPekerja->total() }} Data</span>
            </div>
            <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div class="table-responsive">
                    <table class="table table-lada-sdm align-middle m-0" style="min-width: 600px;">
                        <thead>
                            <tr>
                                <th>Pekerja</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Durasi</th>
                                <th>Tarif</th>
                                <th>Total Upah</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 50px;"></th>
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

                                    // Style class based on job role
                                    $jobClass = 'panen-lada';
                                    if ($pekerja->jenis_pekerjaan == 'Pemupukan Rutin') $jobClass = 'pemupukan-rutin';
                                    if ($pekerja->jenis_pekerjaan == 'Pembersihan Lahan') $jobClass = 'pembersihan-lahan';

                                    // Mock Verification Status
                                    $isVerified = ($pekerja->id % 3 != 0);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="worker-initial-avatar">{{ $initials }}</div>
                                            <span class="fw-bold text-dark">{{ $pekerja->nama_pekerja }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-job {{ $jobClass }}">
                                            {{ $pekerja->jenis_pekerjaan }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ $pekerja->jumlah_hari }} Hari</td>
                                    <td class="text-muted">Rp {{ number_format($pekerja->upah_harian, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($pekerja->total_gaji, 0, ',', '.') }}</td>
                                    <td>
                                        @if($isVerified)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.72rem; font-weight: 700;">Selesai</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill" style="font-size: 0.72rem; font-weight: 700;">Menunggu Verifikasi</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('sdm.edit', $pekerja->id) }}">
                                                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('sdm.destroy', $pekerja->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data upah ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fa-solid fa-trash me-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                        <p class="m-0">Belum ada data pencatatan upah buruh Lada.</p>
                                    </td>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Calculate estimate on initial load
        calculateTotal();

        // Listen for inputs
        $('#jumlah_hari, #upah_harian').on('input change', function() {
            calculateTotal();
        });

        // Set date to today's date
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_kerja').val(today);
    });

    function setJob(jobName, standardRate) {
        // Remove active class from all options
        $('#option-panen, #option-pupuk, #option-pembersihan').removeClass('active');
        
        // Find option & set checked radio
        if (jobName === 'Panen Lada') {
            $('#option-panen').addClass('active');
            $('#option-panen input[type="radio"]').prop('checked', true);
        } else if (jobName === 'Pemupukan Rutin') {
            $('#option-pupuk').addClass('active');
            $('#option-pupuk input[type="radio"]').prop('checked', true);
        } else if (jobName === 'Pembersihan Lahan') {
            $('#option-pembersihan').addClass('active');
            $('#option-pembersihan input[type="radio"]').prop('checked', true);
        }

        // Set default standard rate
        $('#upah_harian').val(standardRate);

        // Calculate estimate
        calculateTotal();
    }

    function calculateTotal() {
        const days = parseFloat($('#jumlah_hari').val()) || 0;
        const rate = parseFloat($('#upah_harian').val()) || 0;
        const total = days * rate;

        const formatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(total);

        $('#salary-preview').text(formatted.replace('IDR', 'Rp'));
        $('#salary-formula').text(`${days} hari x Rp ${rate.toLocaleString('id-ID')} / hari`);
    }
</script>
@endsection
