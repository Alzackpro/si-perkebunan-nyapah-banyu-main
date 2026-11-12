@extends('layouts.app')

@section('title', 'Tambah Area Kebun Baru')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Tambah Lahan Baru</h2>
        <p class="text-muted m-0">Input detail informasi area lahan perkebunan baru ke sistem</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('area-kebun.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fa-solid fa-square-plus me-1"></i> Form Data Area Kebun
    </div>
    <div class="card-body p-4">
        <!-- Error validation message display -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4">
                <h6 class="fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Perbaiki Kesalahan Input:</h6>
                <ul class="m-0 ps-3" style="font-size: 0.88rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('area-kebun.store') }}">
            @csrf

            <div class="row g-4">
                <!-- Nama Lahan -->
                <div class="col-md-6">
                    <label for="nama_lahan" class="form-label fw-semibold">
                        <i class="fa-solid fa-file-signature text-success me-1"></i> Nama Lahan / Blok <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama_lahan" id="nama_lahan" class="form-control @error('nama_lahan') is-invalid @enderror" placeholder="Contoh: Kebun Kopi Blok A1" value="{{ old('nama_lahan') }}" required>
                    @error('nama_lahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jenis Tanaman -->
                <div class="col-md-6">
                    <label for="jenis_tanaman" class="form-label fw-semibold">
                        <i class="fa-solid fa-seedling text-success me-1"></i> Jenis Komoditas Tanaman <span class="text-danger">*</span>
                    </label>
                    <select name="jenis_tanaman" id="jenis_tanaman" class="form-select @error('jenis_tanaman') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Komoditas --</option>
                        <option value="kopi" {{ old('jenis_tanaman') == 'kopi' ? 'selected' : '' }}>Kopi</option>
                        <option value="sawit" {{ old('jenis_tanaman') == 'sawit' ? 'selected' : '' }}>Kelapa Sawit</option>
                        <option value="lada" {{ old('jenis_tanaman') == 'lada' ? 'selected' : '' }}>Lada</option>
                    </select>
                    @error('jenis_tanaman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Luas Hektar -->
                <div class="col-md-4">
                    <label for="luas_hektar" class="form-label fw-semibold">
                        <i class="fa-solid fa-ruler-combined text-success me-1"></i> Luas Lahan (Hektar) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="luas_hektar" id="luas_hektar" class="form-control @error('luas_hektar') is-invalid @enderror" placeholder="Contoh: 4.5" value="{{ old('luas_hektar') }}" required>
                        <span class="input-group-text bg-light text-muted">Ha</span>
                    </div>
                    @error('luas_hektar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jumlah Tanaman -->
                <div class="col-md-4">
                    <label for="jumlah_tanaman" class="form-label fw-semibold">
                        <i class="fa-solid fa-tree-city text-success me-1"></i> Jumlah Tanaman (Batang) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" name="jumlah_tanaman" id="jumlah_tanaman" class="form-control @error('jumlah_tanaman') is-invalid @enderror" placeholder="Contoh: 1500" value="{{ old('jumlah_tanaman') }}" required>
                        <span class="input-group-text bg-light text-muted">Pohon</span>
                    </div>
                    @error('jumlah_tanaman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Usia Tanaman -->
                <div class="col-md-4">
                    <label for="usia_tanaman" class="form-label fw-semibold">
                        <i class="fa-solid fa-hourglass-half text-success me-1"></i> Usia Tanaman (Bulan) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" name="usia_tanaman" id="usia_tanaman" class="form-control @error('usia_tanaman') is-invalid @enderror" placeholder="Contoh: 24" value="{{ old('usia_tanaman') }}" required>
                        <span class="input-group-text bg-light text-muted">Bulan</span>
                    </div>
                    @error('usia_tanaman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Tanam -->
                <div class="col-md-4">
                    <label for="tanggal_tanam" class="form-label fw-semibold">
                        <i class="fa-regular fa-calendar-plus text-success me-1"></i> Tanggal Penanaman <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="tanggal_tanam" id="tanggal_tanam" class="form-control @error('tanggal_tanam') is-invalid @enderror" value="{{ old('tanggal_tanam') }}" required>
                    @error('tanggal_tanam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Estimasi Panen -->
                <div class="col-md-4">
                    <label for="estimasi_panen" class="form-label fw-semibold">
                        <i class="fa-regular fa-calendar-check text-success me-1"></i> Estimasi Panen Berikutnya <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="estimasi_panen" id="estimasi_panen" class="form-control @error('estimasi_panen') is-invalid @enderror" value="{{ old('estimasi_panen') }}" required>
                    @error('estimasi_panen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Lahan -->
                <div class="col-md-4">
                    <label for="status_lahan" class="form-label fw-semibold">
                        <i class="fa-solid fa-flag text-success me-1"></i> Status Lahan Kebun <span class="text-danger">*</span>
                    </label>
                    <select name="status_lahan" id="status_lahan" class="form-select @error('status_lahan') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Status --</option>
                        <option value="produktif" {{ old('status_lahan') == 'produktif' ? 'selected' : '' }}>Produktif</option>
                        <option value="perawatan" {{ old('status_lahan') == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
                        <option value="peremajaan" {{ old('status_lahan') == 'peremajaan' ? 'selected' : '' }}>Peremajaan</option>
                        <option value="baru_tanam" {{ old('status_lahan') == 'baru_tanam' ? 'selected' : '' }}>Baru di Tanam</option>
                    </select>
                    @error('status_lahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mt-5 d-flex gap-3">
                <button type="submit" class="btn btn-success px-4 py-2">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Data Lahan
                </button>
                <a href="{{ route('area-kebun.index') }}" class="btn btn-outline-secondary px-4 py-2">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisTanaman = document.getElementById('jenis_tanaman');
    const usiaTanaman = document.getElementById('usia_tanaman');
    const tanggalTanam = document.getElementById('tanggal_tanam');
    const estimasiPanen = document.getElementById('estimasi_panen');

    function formatDateLocal(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function calculateDates() {
        const jenis = jenisTanaman.value;
        const usia = parseInt(usiaTanaman.value);

        if (isNaN(usia) || usia < 0 || !jenis) return;

        let tglTanamDate = new Date();
        tglTanamDate.setMonth(tglTanamDate.getMonth() - usia);
        
        const today = new Date();
        if (tglTanamDate.getDate() !== today.getDate()) {
            tglTanamDate.setDate(0);
        }
        
        tanggalTanam.value = formatDateLocal(tglTanamDate);
        updateEstimasiPanen(tglTanamDate, jenis);
    }

    function updateEstimasiPanen(baseDate, jenis) {
        let bulanEstimasi = 48; // default
        if (jenis === 'kopi' || jenis === 'sawit') {
            bulanEstimasi = 48;
        } else if (jenis === 'lada') {
            bulanEstimasi = 36;
        }

        let estimasiDate = new Date(baseDate);
        estimasiDate.setMonth(estimasiDate.getMonth() + bulanEstimasi);
        
        if (estimasiDate.getDate() !== baseDate.getDate()) {
            estimasiDate.setDate(0);
        }
        
        estimasiPanen.value = formatDateLocal(estimasiDate);
    }

    // Listener jika usia atau jenis komoditas diubah
    jenisTanaman.addEventListener('change', function() {
        if (tanggalTanam.value) {
            updateEstimasiPanen(new Date(tanggalTanam.value), jenisTanaman.value);
        } else {
            calculateDates();
        }
    });
    usiaTanaman.addEventListener('input', calculateDates);

    // Listener jika tanggal_tanam diubah manual
    tanggalTanam.addEventListener('change', function() {
        const jenis = jenisTanaman.value;
        if (this.value && jenis) {
            updateEstimasiPanen(new Date(this.value), jenis);
        }
    });
});
</script>
@endsection
