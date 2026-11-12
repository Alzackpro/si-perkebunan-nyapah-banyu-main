@extends('layouts.app')

@section('title', 'Catat Perawatan Kebun')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('operasional.index') }}" class="text-decoration-none text-success">Operasional Kebun</a> 
            <span class="text-muted fs-4">/ Catat Perawatan</span>
        </h2>
        <p class="text-muted m-0">Tambahkan riwayat aktivitas perawatan lahan (Pemupukan & Obat Hama)</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('operasional.store') }}" method="POST">
            @csrf

            <!-- BARIS 1: JADWAL & AREA -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_jadwal" class="form-label fw-bold text-dark">JADWAL KEGIATAN</label>
                    <select name="id_jadwal" id="id_jadwal" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        <option value="">-- Terisi Otomatis --</option>
                        @foreach($jadwal as $jdw)
                            <option value="{{ $jdw->id_jadwal }}" {{ (old('id_jadwal') ?? $defaultJadwal) == $jdw->id_jadwal ? 'selected' : '' }}>
                                {{ $jdw->jenis_kegiatan }} ({{ optional($jdw->komoditas)->nama_komoditas }} di {{ optional($jdw->area)->nama_area }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="id_area" class="form-label fw-bold text-dark">AREA LAHAN</label>
                    <select name="id_area" id="id_area" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        <option value="">-- Terisi Otomatis --</option>
                        <!-- Area Lahan tidak perlu di-looping detailnya, cukup kirim value defaultArea, atau buat input hidden saja jika lebih baik, tapi select readonly lebih jelas untuk user -->
                        <option value="{{ $defaultArea }}" selected>Lahan Kebun {{ optional(App\Models\Komoditas::find($defaultKomoditas))->nama_komoditas }}</option>
                    </select>
                </div>
            </div>

            <!-- BARIS 2: KOMODITAS & PEGAWAI -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_komoditas" class="form-label fw-bold text-dark">KOMODITAS</label>
                    <select name="id_komoditas" id="id_komoditas" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        <option value="">-- Terisi Otomatis --</option>
                        @foreach($komoditas as $kom)
                            <option value="{{ $kom->id_komoditas }}" {{ (old('id_komoditas') ?? $defaultKomoditas) == $kom->id_komoditas ? 'selected' : '' }}>
                                {{ $kom->nama_komoditas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="id_pegawai" class="form-label fw-bold text-dark">PEGAWAI PELAKSANA</label>
                    <select name="id_pegawai" id="id_pegawai" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        <option value="">-- Terisi Otomatis --</option>
                        @foreach($pegawai as $peg)
                            <option value="{{ $peg->id_pegawai }}" {{ (old('id_pegawai') ?? $defaultPegawai) == $peg->id_pegawai ? 'selected' : '' }}>
                                {{ $peg->nama_pegawai }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- BARIS 3: PERALATAN & PERLENGKAPAN -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">PERALATAN (ALAT KERJA)</label>
                    <div class="border rounded p-3 bg-white" style="max-height: 150px; overflow-y: auto;">
                        @foreach($peralatan as $alat)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="id_peralatan[]" value="{{ $alat->id_peralatan }}" id="alat_{{ $alat->id_peralatan }}" {{ in_array($alat->id_peralatan, old('id_peralatan', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="alat_{{ $alat->id_peralatan }}">
                                    {{ $alat->nama_peralatan }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('id_peralatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">PERLENGKAPAN</label>
                    <div class="border rounded p-3 bg-white" style="max-height: 150px; overflow-y: auto;">
                        @foreach($perlengkapan as $lengkap)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="id_perlengkapan[]" value="{{ $lengkap->id_perlengkapan }}" id="lengkap_{{ $lengkap->id_perlengkapan }}" {{ in_array($lengkap->id_perlengkapan, old('id_perlengkapan', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="lengkap_{{ $lengkap->id_perlengkapan }}">
                                    {{ $lengkap->nama_perlengkapan }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('id_perlengkapan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 4: JENIS PERAWATAN & TANGGAL -->
            <div class="row mb-4 p-4 rounded border bg-light">
                <div class="col-md-7">
                    <label class="form-label fw-bold text-dark mb-3">TENTUKAN JENIS PERAWATAN YANG DILAKUKAN</label>
                    <div class="d-flex flex-column gap-3">
                        <div class="form-check">
                            <input class="form-check-input fs-5" type="radio" name="jenis_perawatan" id="perawatan_1" value="Pemupukan" {{ old('jenis_perawatan', 'Pemupukan') == 'Pemupukan' ? 'checked' : '' }} required>
                            <label class="form-check-label fs-5 fw-bold text-success mt-1 ms-1" for="perawatan_1">
                                <i class="fa-solid fa-seedling me-1"></i> Pemupukan Tanaman
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input fs-5" type="radio" name="jenis_perawatan" id="perawatan_2" value="Obat Hama" {{ old('jenis_perawatan') == 'Obat Hama' ? 'checked' : '' }} required>
                            <label class="form-check-label fs-5 fw-bold text-warning mt-1 ms-1" for="perawatan_2">
                                <i class="fa-solid fa-bug-slash me-1"></i> Pemberantasan Obat Hama
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input fs-5" type="radio" name="jenis_perawatan" id="perawatan_3" value="Pemupukan dan Obat Hama" {{ old('jenis_perawatan') == 'Pemupukan dan Obat Hama' ? 'checked' : '' }} required>
                            <label class="form-check-label fs-5 fw-bold text-primary mt-1 ms-1" for="perawatan_3">
                                <i class="fa-solid fa-layer-group me-1"></i> Keduanya (Pemupukan & Obat Hama)
                            </label>
                        </div>
                    </div>
                    @error('jenis_perawatan')
                        <div class="text-danger mt-2 small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-5">
                    <label for="tanggal_perawatan" class="form-label fw-bold text-dark">TANGGAL AKTIVITAS</label>
                    <input type="date" name="tanggal_perawatan" id="tanggal_perawatan" class="form-control form-control-lg @error('tanggal_perawatan') is-invalid @enderror" value="{{ old('tanggal_perawatan', date('Y-m-d')) }}" required>
                    @error('tanggal_perawatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 5: KETERANGAN -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan jenis pupuk atau obat yang dipakai (contoh: 'Pakai pupuk urea 2 sak')">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('operasional.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fa-solid fa-save me-1"></i> Simpan Catatan Perawatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
