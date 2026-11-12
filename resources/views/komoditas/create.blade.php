@extends('layouts.app')

@section('title', 'Tambah Komoditas Baru')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('komoditas.index') }}" class="text-decoration-none text-success">Master Komoditas</a> 
            <span class="text-muted fs-4">/ Tambah Baru</span>
        </h2>
        <p class="text-muted m-0">Daftarkan jenis tanaman baru yang ada di area perkebunan</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('komoditas.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="nama_komoditas" class="form-label fw-bold text-dark">NAMA KOMODITAS</label>
                    <input type="text" name="nama_komoditas" id="nama_komoditas" class="form-control @error('nama_komoditas') is-invalid @enderror" value="{{ old('nama_komoditas') }}" placeholder="Contoh: Kopi Robusta, Kelapa Sawit, Lada Hitam..." required>
                    @error('nama_komoditas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Tambahkan deskripsi atau informasi tambahan terkait komoditas ini">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('komoditas.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fa-solid fa-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
