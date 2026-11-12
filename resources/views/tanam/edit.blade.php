@extends('layouts.app')

@section('title', 'Ubah Catatan Tanam')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('tanam.index') }}" class="text-decoration-none text-success">Aktivitas Tanam</a> 
            <span class="text-muted fs-4">/ Ubah Catatan</span>
        </h2>
        <p class="text-muted m-0">Ubah rincian data penanaman atau penyulaman</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('tanam.update', $tanam->id_tanam) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_area" class="form-label fw-bold text-dark">AREA / LAHAN KEBUN</label>
                    <select name="id_area" id="id_area" class="form-select @error('id_area') is-invalid @enderror" required>
                        <option value="">-- Pilih Area / Lahan --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}" {{ (old('id_area') ?? $tanam->id_area) == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_lahan }} - {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_area')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="tanggal_tanam" class="form-label fw-bold text-dark">TANGGAL PENANAMAN</label>
                    <input type="date" name="tanggal_tanam" id="tanggal_tanam" class="form-control @error('tanggal_tanam') is-invalid @enderror" value="{{ old('tanggal_tanam', $tanam->tanggal_tanam) }}" required>
                    @error('tanggal_tanam')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="p-4 rounded border" style="background-color: #f8f9fa;">
                        <label class="form-label fw-bold text-primary mb-3"><i class="fa-solid fa-seedling me-1"></i> JUMLAH YANG DITANAM / DISULAM</label>

                        <div class="row align-items-center mt-2">
                            <div class="col-md-5">
                                <label for="jumlah_tanam" class="form-label fw-bold text-dark">JUMLAH BIBIT</label>
                                <div class="input-group">
                                    <input type="number" name="jumlah_tanam" id="jumlah_tanam" class="form-control form-control-lg border-primary text-primary fw-bold @error('jumlah_tanam') is-invalid @enderror" value="{{ old('jumlah_tanam', $tanam->jumlah_tanam) }}" min="1" required placeholder="0">
                                    <span class="input-group-text bg-white fw-bold text-primary">Pohon / Bibit</span>
                                </div>
                                @error('jumlah_tanam')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $tanam->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('tanam.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-1"></i> Perbarui Catatan Tanam
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
