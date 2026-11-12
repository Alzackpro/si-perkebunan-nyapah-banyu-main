@extends('layouts.app')

@section('title', 'Ubah Akun Pengguna')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('user.index') }}" class="text-decoration-none text-success">Master User</a> 
            <span class="text-muted fs-4">/ Ubah Akun</span>
        </h2>
        <p class="text-muted m-0">Ubah detail kredensial dan hak akses akun</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('user.update', $user->id_user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="username" class="form-label fw-bold text-dark">USERNAME (NAMA LOGIN)</label>
                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                    <div class="form-text">Maksimal 20 karakter tanpa spasi.</div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="email" class="form-label fw-bold text-dark">ALAMAT EMAIL</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="alert alert-warning mb-4 mt-2">
                <i class="fa-solid fa-circle-info me-1"></i> Biarkan kolom kata sandi <strong>kosong</strong> jika Anda tidak ingin mengubahnya.
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label fw-bold text-dark">KATA SANDI BARU (OPSIONAL)</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-bold text-dark">KONFIRMASI KATA SANDI BARU</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="role" class="form-label fw-bold text-dark">HAK AKSES (ROLE)</label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="owner" {{ old('role', strtolower($user->role)) == 'owner' ? 'selected' : '' }}>Owner (Pemilik Utama)</option>
                        <option value="pengurus" {{ old('role', strtolower($user->role)) == 'pengurus' ? 'selected' : '' }}>Pengurus (Akses Terbatas Sesuai Kebun)</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('user.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-1"></i> Perbarui Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
