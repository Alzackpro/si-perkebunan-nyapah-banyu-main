@extends('layouts.app')

@section('title', 'Ubah Hasil Panen')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('panen.index') }}" class="text-decoration-none text-success">Hasil Panen</a> 
            <span class="text-muted fs-4">/ Ubah Data</span>
        </h2>
        <p class="text-muted m-0">Koreksi pencatatan hasil timbangan panen kotor dan bersih</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('panen.update', $panen->id_panen) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- BARIS 1: JADWAL & AREA -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_jadwal" class="form-label fw-bold text-dark">JADWAL KEGIATAN</label>
                    <select name="id_jadwal" id="id_jadwal" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        @foreach($jadwal as $jdw)
                            <option value="{{ $jdw->id_jadwal }}" {{ old('id_jadwal', $panen->id_jadwal) == $jdw->id_jadwal ? 'selected' : '' }}>
                                {{ $jdw->jenis_kegiatan }} ({{ optional($jdw->komoditas)->nama_komoditas }} di {{ optional($jdw->area)->nama_area }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="id_area" class="form-label fw-bold text-dark">AREA LAHAN (YANG SUDAH PRODUKTIF)</label>
                    <select name="id_area" id="id_area" class="form-select @error('id_area') is-invalid @enderror" required>
                        <option value="">-- Pilih Area Lahan --</option>
                        @foreach($area as $lahan)
                            <option value="{{ $lahan->id_area }}" {{ old('id_area', $panen->id_area) == $lahan->id_area ? 'selected' : '' }}>
                                {{ $lahan->nama_area }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_area')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 2: KOMODITAS & TANGGAL -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="id_komoditas" class="form-label fw-bold text-dark">KOMODITAS</label>
                    <select name="id_komoditas" id="id_komoditas" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        @foreach($komoditas as $kom)
                            <option value="{{ $kom->id_komoditas }}" {{ old('id_komoditas', $panen->id_komoditas) == $kom->id_komoditas ? 'selected' : '' }}>
                                {{ $kom->nama_komoditas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tanggal" class="form-label fw-bold text-dark">TANGGAL PELAKSANAAN PANEN</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $panen->tanggal) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 3: PERALATAN & PERLENGKAPAN -->
            @php
                $selectedPeralatan = $panen->id_peralatan ? explode(',', $panen->id_peralatan) : [];
                $selectedPerlengkapan = $panen->id_perlengkapan ? explode(',', $panen->id_perlengkapan) : [];
            @endphp
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark">PERALATAN (ALAT KERJA)</label>
                    <div class="p-3 border rounded bg-white" style="max-height: 200px; overflow-y: auto;">
                        @foreach($peralatan as $alat)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="id_peralatan[]" value="{{ $alat->id_peralatan }}" id="alat_{{ $alat->id_peralatan }}" {{ in_array($alat->id_peralatan, old('id_peralatan', $selectedPeralatan)) ? 'checked' : '' }}>
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
                                <input class="form-check-input" type="checkbox" name="id_perlengkapan[]" value="{{ $lengkap->id_perlengkapan }}" id="lengkap_{{ $lengkap->id_perlengkapan }}" {{ in_array($lengkap->id_perlengkapan, old('id_perlengkapan', $selectedPerlengkapan)) ? 'checked' : '' }}>
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

            <!-- BARIS 4: TIMBANGAN HASIL PANEN -->
            <div class="row mb-4 p-4 rounded border bg-light">
                <div class="col-12 mb-2">
                    <label class="form-label fw-bold text-dark m-0">KOREKSI TIMBANGAN HASIL PANEN (Kilogram)</label>
                </div>
                
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="gross_panen" class="form-label fw-bold text-secondary">HASIL PANEN KOTOR (GROSS)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="gross_panen" id="gross_panen" class="form-control form-control-lg @error('gross_panen') is-invalid @enderror" value="{{ old('gross_panen', $panen->gross_panen) }}" placeholder="0.00" required>
                        <span class="input-group-text bg-white fw-bold">Kg</span>
                    </div>
                    @error('gross_panen')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="netto_panen" class="form-label fw-bold text-success"><i class="fa-solid fa-scale-balanced me-1"></i> HASIL PANEN BERSIH (NETTO)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="netto_panen" id="netto_panen" class="form-control form-control-lg border-success text-success fw-bold @error('netto_panen') is-invalid @enderror" value="{{ old('netto_panen', $panen->netto_panen) }}" placeholder="0.00" required>
                        <span class="input-group-text bg-success text-white fw-bold">Kg</span>
                    </div>
                    @error('netto_panen')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 5: KETERANGAN -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan (contoh: 'Kondisi cuaca hujan saat panen' atau 'Panen blok timur')">{{ old('keterangan', $panen->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('panen.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-1"></i> Perbarui Hasil Panen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
