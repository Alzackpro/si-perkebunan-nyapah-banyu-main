@extends('layouts.app')

@section('title', 'Input Hasil Pengecekan Lahan')

@section('content')
<div class="row mb-4">
    <div class="col">
        <a href="{{ route('hasil-pengecekan.index') }}" class="btn btn-outline-secondary rounded-3 mb-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
        <h2 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">Input Hasil Pengecekan</h2>
        <p class="text-muted">Isi formulir laporan pengecekan harian pada area kebun operasional.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                
                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('hasil-pengecekan.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label fw-semibold text-dark">Tanggal Pengecekan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="id_area" class="form-label fw-semibold text-dark">Kebun yang Dicek <span class="text-danger">*</span></label>
                            <select class="form-select bg-light" id="id_area" name="id_area" required>
                                <option value="">-- Pilih Area Kebun --</option>
                                @foreach($areaKebun as $area)
                                    <option value="{{ $area->id_area }}" {{ old('id_area') == $area->id_area ? 'selected' : '' }}>
                                        {{ $area->nama_area }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-block">Aktivitas Pengecekan <span class="text-danger">*</span></label>
                        <div class="card border border-light-subtle bg-light rounded-3 p-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="aktivitas" id="aktivitas_1" value="Apakah area kebun ada yang perlu dibersihkan" {{ old('aktivitas') == 'Apakah area kebun ada yang perlu dibersihkan' ? 'checked' : '' }} required>
                                <label class="form-check-label text-dark" for="aktivitas_1">
                                    Apakah area kebun ada yang perlu dibersihkan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="aktivitas" id="aktivitas_2" value="Sensus tanaman berkala" {{ old('aktivitas') == 'Sensus tanaman berkala' ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="aktivitas_2">
                                    Sensus tanaman berkala
                                </label>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mt-5 mb-3 text-dark border-bottom pb-2">Logistik & Peralatan</h6>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="id_peralatan" class="form-label fw-semibold text-dark">Alat yang Digunakan</label>
                            <select class="form-select bg-light" id="id_peralatan" name="id_peralatan">
                                <option value="">-- Pilih Peralatan (Opsional) --</option>
                                @foreach($peralatan as $alat)
                                    <option value="{{ $alat->id_peralatan }}" {{ old('id_peralatan') == $alat->id_peralatan ? 'selected' : '' }}>
                                        {{ $alat->nama_peralatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_perlengkapan" class="form-label fw-semibold text-dark">Perlengkapan Tambahan</label>
                            <select class="form-select bg-light" id="id_perlengkapan" name="id_perlengkapan">
                                <option value="">-- Pilih Perlengkapan (Opsional) --</option>
                                @foreach($perlengkapan as $perleng)
                                    <option value="{{ $perleng->id_perlengkapan }}" {{ old('id_perlengkapan') == $perleng->id_perlengkapan ? 'selected' : '' }}>
                                        {{ $perleng->nama_perlengkapan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h6 class="fw-bold mt-5 mb-3 text-dark border-bottom pb-2">Data Penyulaman (Jika Ada)</h6>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="id_komoditas_bibit" class="form-label fw-semibold text-dark">Kebutuhan Penyulaman Tanaman</label>
                            <select class="form-select bg-light" id="id_komoditas_bibit" name="id_komoditas_bibit">
                                <option value="">-- Pilih Jenis Bibit (Opsional) --</option>
                                @foreach($komoditas as $komo)
                                    <option value="{{ $komo->id_komoditas }}" {{ old('id_komoditas_bibit') == $komo->id_komoditas ? 'selected' : '' }}>
                                        {{ $komo->nama_komoditas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="jumlah_bibit" class="form-label fw-semibold text-dark">Jumlah Bibit yang Dibutuhkan</label>
                            <div class="input-group">
                                <input type="number" class="form-control bg-light" id="jumlah_bibit" name="jumlah_bibit" value="{{ old('jumlah_bibit') }}" min="0">
                                <span class="input-group-text bg-white">Bibit</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 d-flex justify-content-end gap-3">
                        <a href="{{ route('hasil-pengecekan.index') }}" class="btn btn-light border px-4 py-2 rounded-3">Batal</a>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-3" style="background-color: #059669; border-color: #059669;">
                            <i class="fa-solid fa-save me-1"></i> Simpan Data Pengecekan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 d-none d-lg-block">
        <div class="card border-0 shadow-sm rounded-4 bg-success bg-opacity-10 mt-4">
            <div class="card-body p-4 text-center">
                <i class="fa-solid fa-seedling text-success mb-3" style="font-size: 3rem;"></i>
                <h5 class="fw-bold text-dark">Petunjuk Pengisian</h5>
                <p class="text-muted small mb-0 mt-2 text-start">
                    • <b>Tanggal</b>: Wajib diisi dengan hari pelaksanaan.<br>
                    • <b>Kebun</b>: Menampilkan area yang di bawah tanggung jawab Anda.<br>
                    • <b>Aktivitas</b>: Pilih aktivitas utama pengecekan.<br>
                    • <b>Penyulaman</b>: Hanya diisi jika Anda menemukan tanaman mati/rusak yang perlu diganti.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
