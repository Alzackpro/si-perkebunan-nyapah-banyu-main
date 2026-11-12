@extends('layouts.app')

@section('title', 'Tambah Pegawai Baru')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('pegawai.index') }}" class="text-decoration-none text-success">Master Pegawai</a> 
            <span class="text-muted fs-4">/ Tambah Baru</span>
        </h2>
        <p class="text-muted m-0">Masukkan data pegawai baru ke dalam sistem</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_pegawai" class="form-label fw-bold text-dark">NAMA PEGAWAI</label>
                    <input type="text" name="nama_pegawai" id="nama_pegawai" class="form-control @error('nama_pegawai') is-invalid @enderror" value="{{ old('nama_pegawai') }}" placeholder="Contoh: Budi Santoso" required>
                    @error('nama_pegawai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="id_user" class="form-label fw-bold text-dark">TAUTAN AKUN (USER LOGIN)</label>
                    <select name="id_user" id="id_user" class="form-select @error('id_user') is-invalid @enderror" required>
                        <option value="">-- Pilih Akun User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id_user }}" {{ old('id_user') == $user->id_user ? 'selected' : '' }}>
                                {{ $user->username }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="jabatan" class="form-label fw-bold text-dark">JABATAN</label>
                    <select name="jabatan" id="jabatan" class="form-select @error('jabatan') is-invalid @enderror" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Owner" {{ old('jabatan') == 'Owner' ? 'selected' : '' }}>Owner</option>
                        <option value="Pengurus" {{ old('jabatan') == 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                        <option value="Mandor" {{ old('jabatan') == 'Mandor' ? 'selected' : '' }}>Mandor</option>
                    </select>
                    @error('jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="no_tlp" class="form-label fw-bold text-dark">NOMOR TELEPON</label>
                    <input type="text" name="no_tlp" id="no_tlp" class="form-control @error('no_tlp') is-invalid @enderror" value="{{ old('no_tlp') }}" placeholder="08xxxxxxxxxx">
                    @error('no_tlp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="status" class="form-label fw-bold text-dark">STATUS PEGAWAI</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <label for="alamat" class="form-label fw-bold text-dark">ALAMAT LENGKAP</label>
                    <textarea name="alamat" id="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('pegawai.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fa-solid fa-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
