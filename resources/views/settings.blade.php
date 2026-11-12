@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Pengaturan Profil</h2>
        <p class="text-muted m-0">Kelola informasi pribadi dan keamanan akun Anda</p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: User Profile Detail Overview Card -->
    <div class="col-lg-4">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body text-center p-4">
                <div class="position-relative d-inline-block mb-3">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Avatar" class="rounded-circle border border-4 border-success-subtle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    <span class="position-absolute bottom-0 end-0 bg-success text-white p-2 rounded-circle shadow-sm" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fa-solid fa-user-shield"></i>
                    </span>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                <p class="text-success fw-semibold mb-3" style="text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                    <i class="fa-solid fa-shield-halved me-1"></i> {{ $user->role }}
                </p>
                <hr class="text-muted opacity-25">
                <div class="text-start mt-3">
                    <div class="mb-2">
                        <small class="text-muted d-block">E-mail Terdaftar</small>
                        <span class="text-dark fw-medium"><i class="fa-regular fa-envelope text-success me-2"></i> {{ $user->email }}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Tanggal Terdaftar</small>
                        <span class="text-dark fw-medium"><i class="fa-regular fa-calendar text-success me-2"></i> {{ $user->created_at->locale('id')->isoFormat('LL') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Settings Edit Form Card -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fa-solid fa-user-gear me-2"></i> Form Pengaturan Akun</h6>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4">
                        <h6 class="fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Perbaiki Kesalahan Input:</h6>
                        <ul class="m-0 ps-3" style="font-size: 0.88rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    
                    <h5 class="fw-bold text-success mb-3 pb-2 border-bottom"><i class="fa-regular fa-id-card me-2"></i> Informasi Profil</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-regular fa-user text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">E-mail Akun <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-regular fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-success mb-3 pb-2 border-bottom mt-4"><i class="fa-solid fa-key me-2"></i> Ganti Password <span class="text-muted" style="font-size: 0.8rem; font-weight: normal;">(Kosongkan jika tidak ingin mengubah password)</span></h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock-open text-muted"></i></span>
                                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password Anda saat ini">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-shield text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
