@extends('layouts.app')

@section('title', 'Tambah Status Baru')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('status.index') }}" class="text-decoration-none text-success">Master Status</a> 
            <span class="text-muted fs-4">/ Tambah Baru</span>
        </h2>
        <p class="text-muted m-0">Daftarkan status baru</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('status.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="nama_status" class="form-label fw-bold text-dark">NAMA STATUS</label>
                    <input type="text" name="nama_status" id="nama_status" class="form-control @error('nama_status') is-invalid @enderror" value="{{ old('nama_status') }}" placeholder="Contoh: Selesai, Pending, Proses..." required>
                    @error('nama_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Tambahkan deskripsi mengenai status ini">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('status.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fa-solid fa-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
