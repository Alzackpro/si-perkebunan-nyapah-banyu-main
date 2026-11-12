@extends('layouts.app')

@section('title', 'Catat Aktivitas Baru')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('pembibitan.index') }}" class="text-decoration-none text-success">Pembibitan</a> 
            <span class="text-muted fs-4">/ Catat Aktivitas</span>
        </h2>
        <p class="text-muted m-0">Tambahkan riwayat aktivitas pengadaan bibit</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('pembibitan.store') }}" method="POST">
            @csrf

            <!-- BARIS 1: JADWAL & TANGGAL -->
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
                    <label for="tanggal_pembibitan" class="form-label fw-bold text-dark">TANGGAL AKTIVITAS</label>
                    <input type="date" name="tanggal_pembibitan" id="tanggal_pembibitan" class="form-control @error('tanggal_pembibitan') is-invalid @enderror" value="{{ old('tanggal_pembibitan', date('Y-m-d')) }}" required>
                    @error('tanggal_pembibitan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                    <div class="p-3 border rounded bg-white" style="max-height: 200px; overflow-y: auto;">
                        @foreach($peralatan as $alat)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="id_peralatan[]" value="{{ $alat->id_peralatan }}" id="alat_{{ $alat->id_peralatan }}" {{ in_array($alat->id_peralatan, old('id_peralatan', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="alat_{{ $alat->id_peralatan }}">
                                    {{ $alat->nama_peralatan }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('id_peralatan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">PERLENGKAPAN</label>
                    <div class="p-3 border rounded bg-white" style="max-height: 200px; overflow-y: auto;">
                        @foreach($perlengkapan as $lengkap)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="id_perlengkapan[]" value="{{ $lengkap->id_perlengkapan }}" id="lengkap_{{ $lengkap->id_perlengkapan }}" {{ in_array($lengkap->id_perlengkapan, old('id_perlengkapan', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="lengkap_{{ $lengkap->id_perlengkapan }}">
                                    {{ $lengkap->nama_perlengkapan }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('id_perlengkapan')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 4: JUMLAH BIBIT -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="p-4 rounded border" style="background-color: #f8f9fa;">
                        <label class="form-label fw-bold text-success mb-3"><i class="fa-solid fa-plus-circle me-1"></i> MENAMBAH STOK BIBIT BARU</label>

                        <div class="row align-items-center mt-2">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="jumlah_bibit" class="form-label fw-bold text-dark" id="label_jumlah">JUMLAH BIBIT YANG DISIAPKAN</label>
                                <div class="input-group">
                                    <input type="number" name="jumlah_bibit" id="jumlah_bibit" class="form-control form-control-lg border-success text-success fw-bold @error('jumlah_bibit') is-invalid @enderror" value="{{ old('jumlah_bibit') }}" min="1" required placeholder="0">
                                    <span class="input-group-text bg-white fw-bold text-success">Bibit</span>
                                </div>
                                @error('jumlah_bibit')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="usia_bibit" class="form-label fw-bold text-dark">USIA BIBIT SAAT INI</label>
                                <div class="input-group">
                                    <input type="number" name="usia_bibit" id="usia_bibit" class="form-control form-control-lg border-primary text-primary fw-bold @error('usia_bibit') is-invalid @enderror" value="{{ old('usia_bibit', 0) }}" min="0" required placeholder="0">
                                    <span class="input-group-text bg-white fw-bold text-primary">Bulan</span>
                                </div>
                                @error('usia_bibit')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info border-info mb-0 mt-3 mt-md-0 h-100 d-flex flex-column justify-content-center">
                                    <h6 class="fw-bold text-info-emphasis mb-1"><i class="fa-solid fa-clock-rotate-left me-1"></i> Estimasi Usia Siap Tanam</h6>
                                    <p class="mb-0 small text-dark" id="estimasi_tanam_text">
                                        Pilih komoditas untuk melihat waktu pembesaran bibit.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BARIS 5: KETERANGAN -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan (contoh: 'Pembibitan gelombang kedua' atau 'Penyulaman blok utara')">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('pembibitan.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fa-solid fa-save me-1"></i> Simpan Catatan Aktivitas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const komoditasSelect = document.getElementById('id_komoditas');
    const tanggalInput = document.getElementById('tanggal_pembibitan');
    const usiaBibitInput = document.getElementById('usia_bibit');
    const estimasiText = document.getElementById('estimasi_tanam_text');

    function calculateEstimasi() {
        const komoditasId = komoditasSelect.value;
        const tanggalVal = tanggalInput.value;
        const usiaBibitVal = parseInt(usiaBibitInput.value) || 0;
        
        if (!komoditasId || !tanggalVal) return;

        let bulanTarget = 0;
        let komoditasName = '';

        if (komoditasId == 1) { // Kopi
            bulanTarget = 6;
            komoditasName = 'Kopi';
        } else if (komoditasId == 2) { // Sawit
            bulanTarget = 12;
            komoditasName = 'Sawit';
        } else if (komoditasId == 3) { // Lada
            bulanTarget = 6;
            komoditasName = 'Lada';
        }

        if (bulanTarget > 0) {
            let sisaBulan = bulanTarget - usiaBibitVal;
            
            if (sisaBulan <= 0) {
                estimasiText.innerHTML = `Bibit <b>${komoditasName}</b> ini sudah berusia <b>${usiaBibitVal} bulan</b> dan <b>SUDAH SIAP TANAM</b> (target: ${bulanTarget} bulan).`;
            } else {
                let tgl = new Date(tanggalVal);
                tgl.setMonth(tgl.getMonth() + sisaBulan);
                
                // Format tanggal (DD MMM YYYY)
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = tgl.toLocaleDateString('id-ID', options);
                
                estimasiText.innerHTML = `Sisa waktu pembesaran: <b>${sisaBulan} bulan lagi</b> (target: ${bulanTarget} bulan).<br>Estimasi siap tanam pada: <b class="text-success">${formattedDate}</b>.`;
            }
        }
    }

    // Since komoditas is auto-filled and read-only for pengurus, trigger it once on load
    if (komoditasSelect.value) {
        calculateEstimasi();
    }

    komoditasSelect.addEventListener('change', calculateEstimasi);
    tanggalInput.addEventListener('change', calculateEstimasi);
    usiaBibitInput.addEventListener('input', calculateEstimasi);
});
</script>
@endsection

