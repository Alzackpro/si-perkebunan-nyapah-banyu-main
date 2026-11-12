@extends('layouts.app')

@section('title', 'Tambah Akun Baru')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('user.index') }}" class="text-decoration-none text-success">Master User</a> 
            <span class="text-muted fs-4">/ Tambah Akun</span>
        </h2>
        <p class="text-muted m-0">Buat akun login baru untuk mengakses sistem</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('user.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="username" class="form-label fw-bold text-dark">USERNAME (NAMA LOGIN)</label>
                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Contoh: pengurus.kopi" required>
                    <div class="form-text">Maksimal 20 karakter tanpa spasi.</div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="email" class="form-label fw-bold text-dark">ALAMAT EMAIL</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Contoh: kopi@nyapahbanyu.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label fw-bold text-dark">KATA SANDI (PASSWORD)</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    <div class="form-text">Minimal 8 karakter.</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-bold text-dark">KONFIRMASI KATA SANDI</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="role" class="form-label fw-bold text-dark">HAK AKSES (ROLE)</label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">-- Pilih Hak Akses --</option>
                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner (Pemilik Utama)</option>
                        <option value="pengurus" {{ old('role') == 'pengurus' ? 'selected' : '' }}>Pengurus (Akses Terbatas Sesuai Kebun)</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('user.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fa-solid fa-save me-1"></i> Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
