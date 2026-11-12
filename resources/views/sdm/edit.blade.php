@extends('layouts.app')

@section('title', 'Ubah Gaji Pekerja / SDM')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none text-success">Operasional SDM</a> 
            <span class="text-muted fs-4">/ Ubah Pembayaran</span>
        </h2>
        <p class="text-muted m-0">Koreksi pencatatan pembayaran upah tenaga kerja harian lepas</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('sdm.update', $sdm->id_upah) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- BARIS 1: JADWAL & AREA -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_jadwal" class="form-label fw-bold text-dark">JADWAL KEGIATAN</label>
                    <select name="id_jadwal" id="id_jadwal" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        @foreach($jadwal as $jdw)
                            <option value="{{ $jdw->id_jadwal }}" {{ old('id_jadwal', $sdm->id_jadwal) == $jdw->id_jadwal ? 'selected' : '' }}>
                                {{ $jdw->jenis_kegiatan }} ({{ optional($jdw->komoditas)->nama_komoditas }} di {{ optional($jdw->area)->nama_area }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="id_area" class="form-label fw-bold text-dark">AREA LAHAN</label>
                    <select name="id_area" id="id_area" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        <option value="{{ $sdm->id_area }}" selected>Lahan Kebun {{ optional($sdm->komoditas)->nama_komoditas ?? '...' }}</option>
                    </select>
                </div>
            </div>

            <!-- BARIS 2: KOMODITAS & TANGGAL -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="id_komoditas" class="form-label fw-bold text-dark">KOMODITAS</label>
                    <select name="id_komoditas" id="id_komoditas" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        @foreach($komoditas as $kom)
                            <option value="{{ $kom->id_komoditas }}" {{ old('id_komoditas', $sdm->id_komoditas) == $kom->id_komoditas ? 'selected' : '' }}>
                                {{ $kom->nama_komoditas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tanggal_bayar" class="form-label fw-bold text-dark">TANGGAL PEMBAYARAN UPAH</label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" value="{{ old('tanggal_bayar', $sdm->tanggal_bayar) }}" required>
                    @error('tanggal_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted mb-4">

            <!-- BARIS 3: NAMA & JENIS PEKERJAAN -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="nama_pekerja" class="form-label fw-bold text-dark">NAMA PEKERJA</label>
                    <input type="text" name="nama_pekerja" id="nama_pekerja" class="form-control @error('nama_pekerja') is-invalid @enderror" value="{{ old('nama_pekerja', $sdm->nama_pekerja) }}" placeholder="Contoh: Pak Budi" required>
                    @error('nama_pekerja')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="jenis_pekerjaan" class="form-label fw-bold text-dark">JENIS PEKERJAAN</label>
                    <select name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-select @error('jenis_pekerjaan') is-invalid @enderror" required>
                        <option value="">-- Pilih Pekerjaan --</option>
                        <option value="Panen" {{ old('jenis_pekerjaan', $sdm->jenis_pekerjaan) == 'Panen' ? 'selected' : '' }}>Panen</option>
                        <option value="Pemupukan" {{ old('jenis_pekerjaan', $sdm->jenis_pekerjaan) == 'Pemupukan' ? 'selected' : '' }}>Pemupukan</option>
                        <option value="Obat Hama" {{ old('jenis_pekerjaan', $sdm->jenis_pekerjaan) == 'Obat Hama' ? 'selected' : '' }}>Penyemprotan Obat Hama</option>
                        <option value="Pembibitan" {{ old('jenis_pekerjaan', $sdm->jenis_pekerjaan) == 'Pembibitan' ? 'selected' : '' }}>Pembibitan / Penyulaman</option>
                    </select>
                    @error('jenis_pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 4: PERHITUNGAN UPAH -->
            <div class="row mb-4 p-4 rounded border bg-light">
                <div class="col-12 mb-2">
                    <label class="form-label fw-bold text-dark m-0">RINCIAN PERHITUNGAN UPAH HARIAN</label>
                    <p class="text-muted small m-0 mb-3">Total upah akan dihitung otomatis: (Jumlah Hari x Tarif Harian).</p>
                </div>
                
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="jumlah_hari" class="form-label fw-bold text-secondary">JUMLAH HARI KERJA</label>
                    <div class="input-group">
                        <input type="number" name="jumlah_hari" id="jumlah_hari" class="form-control form-control-lg @error('jumlah_hari') is-invalid @enderror" value="{{ old('jumlah_hari', $sdm->jumlah_hari) }}" min="1" required>
                        <span class="input-group-text bg-white fw-bold">Hari</span>
                    </div>
                    @error('jumlah_hari')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="tarif_harian" class="form-label fw-bold text-secondary">TARIF PER HARI</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white fw-bold">Rp</span>
                        <input type="number" step="1" name="tarif_harian" id="tarif_harian" class="form-control form-control-lg @error('tarif_harian') is-invalid @enderror" value="{{ old('tarif_harian', $sdm->tarif_harian) }}" placeholder="Contoh: 100000" required>
                    </div>
                    @error('tarif_harian')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 5: BUKTI & KETERANGAN -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="bukti_pembayaran" class="form-label fw-bold text-dark">FOTO BUKTI PEMBAYARAN (Opsional)</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control @error('bukti_pembayaran') is-invalid @enderror" accept="image/*">
                    @if($sdm->bukti_pembayaran)
                        <div class="mt-2">
                            <a href="{{ asset($sdm->bukti_pembayaran) }}" target="_blank" class="badge bg-info text-dark text-decoration-none"><i class="fa-solid fa-eye"></i> Lihat Bukti Saat Ini</a>
                        </div>
                    @endif
                    @error('bukti_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan">{{ old('keterangan', $sdm->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('sdm.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-1"></i> Perbarui Catatan Upah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
