@extends('layouts.app')

@section('title', 'Tambah Jadwal Baru')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('jadwal.index') }}" class="text-decoration-none text-success">Master Jadwal</a> 
            <span class="text-muted fs-4">/ Tambah Baru</span>
        </h2>
        <p class="text-muted m-0">Daftarkan jadwal operasional baru</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="jenis_kegiatan" class="form-label fw-bold text-dark">JENIS KEGIATAN</label>
                    <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-select @error('jenis_kegiatan') is-invalid @enderror" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        <option value="panen" {{ old('jenis_kegiatan') == 'panen' ? 'selected' : '' }}>Panen</option>
                        <option value="perawatan" {{ old('jenis_kegiatan') == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
                        <option value="pengiriman" {{ old('jenis_kegiatan') == 'pengiriman' ? 'selected' : '' }}>Pengiriman</option>
                        <option value="pembibitan" {{ old('jenis_kegiatan') == 'pembibitan' ? 'selected' : '' }}>Pembibitan</option>
                    </select>
                    @error('jenis_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="frekuensi" class="form-label fw-bold text-dark">FREKUENSI (KALI DALAM SETAHUN)</label>
                    <input type="number" name="frekuensi" id="frekuensi" class="form-control @error('frekuensi') is-invalid @enderror" value="{{ old('frekuensi', 1) }}" min="1" required>
                    @error('frekuensi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_komoditas" class="form-label fw-bold text-dark">KOMODITAS TERKAIT</label>
                    <select name="id_komoditas" id="id_komoditas" class="form-select @error('id_komoditas') is-invalid @enderror" required>
                        <option value="">-- Pilih Komoditas --</option>
                        @foreach($komoditas as $item)
                            <option value="{{ $item->id_komoditas }}" {{ old('id_komoditas') == $item->id_komoditas ? 'selected' : '' }}>
                                {{ $item->nama_komoditas }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_komoditas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="id_area" class="form-label fw-bold text-dark">AREA KEBUN TERKAIT</label>
                    <select name="id_area" id="id_area" class="form-select @error('id_area') is-invalid @enderror" required>
                        <option value="">-- Pilih Area Kebun --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}" {{ old('id_area') == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_area')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Tambahkan deskripsi mengenai jadwal ini">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('jadwal.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fa-solid fa-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
