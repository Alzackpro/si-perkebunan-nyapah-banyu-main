@extends('layouts.app')

@section('title', 'Ubah Data Perlengkapan')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('perlengkapan.index') }}" class="text-decoration-none text-success">Master Perlengkapan</a> 
            <span class="text-muted fs-4">/ Ubah Data</span>
        </h2>
        <p class="text-muted m-0">Ubah rincian informasi perlengkapan</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('perlengkapan.update', $perlengkapan->id_perlengkapan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="nama_perlengkapan" class="form-label fw-bold text-dark">NAMA PERLENGKAPAN</label>
                    <input type="text" name="nama_perlengkapan" id="nama_perlengkapan" class="form-control @error('nama_perlengkapan') is-invalid @enderror" value="{{ old('nama_perlengkapan', $perlengkapan->nama_perlengkapan) }}" required>
                    @error('nama_perlengkapan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $perlengkapan->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('perlengkapan.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-1"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
