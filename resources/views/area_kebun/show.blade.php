@extends('layouts.app')

@section('title', 'Detail Area Kebun')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Detail Area Kebun</h2>
        <p class="text-muted m-0">Informasi lengkap lahan: <strong>{{ $lahan->nama_lahan }}</strong></p>
    </div>
    <div class="col-auto d-flex gap-2">
        <a href="{{ route('area-kebun.edit', $lahan->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square me-1"></i> Ubah
        </a>
        <a href="{{ route('area-kebun.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Informasi Utama -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <i class="fa-solid fa-info-circle me-1"></i> Informasi Dasar Lahan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Nama Lahan / Blok</label>
                        <h5 class="mb-0">{{ $lahan->nama_lahan }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Jenis Komoditas</label>
                        {!! $lahan->badge_jenis_tanaman !!}
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Luas Lahan</label>
                        <h5 class="mb-0">{{ number_format($lahan->luas_hektar, 2, ',', '.') }} <small class="text-muted">Hektar</small></h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Jumlah Tanaman</label>
                        <h5 class="mb-0">{{ number_format($lahan->jumlah_tanaman, 0, ',', '.') }} <small class="text-muted">Batang</small></h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Usia Tanaman</label>
                        <h5 class="mb-0">{{ $lahan->usia_tanaman }} <small class="text-muted">Bulan</small></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <i class="fa-solid fa-calendar-days me-1"></i> Informasi Penanaman & Panen
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Tanggal Penanaman</label>
                        <h5 class="mb-0"><i class="fa-regular fa-calendar-plus text-success me-2"></i>{{ date('d-m-Y', strtotime($lahan->tanggal_tanam)) }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Estimasi Panen Berikutnya</label>
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 me-2"><i class="fa-regular fa-calendar-check text-info me-2"></i>{{ date('d-m-Y', strtotime($lahan->estimasi_panen)) }}</h5>
                            @if($lahan->mendekati_panen)
                                <span class="badge bg-danger">Mendekati Masa Panen!</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <i class="fa-solid fa-flag me-1"></i> Status & Keterangan
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="fw-semibold text-muted mb-1 d-block">Status Lahan</label>
                        {!! $lahan->badge_status !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="fw-semibold text-muted mb-1 d-block">Keterangan</label>
                        @if($lahan->keterangan)
                            <p class="mb-0">{{ $lahan->keterangan }}</p>
                        @else
                            <p class="text-muted mb-0"><em>Tidak ada keterangan.</em></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Ringkasan -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="fa-solid fa-chart-pie me-1"></i> Ringkasan
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-ruler-combined text-success me-2"></i> Luas Total</span>
                        <strong>{{ number_format($lahan->luas_hektar, 2, ',', '.') }} Ha</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-tree text-info me-2"></i> Jumlah Pohon</span>
                        <strong>{{ number_format($lahan->jumlah_tanaman, 0, ',', '.') }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-clock text-warning me-2"></i> Usia Tanaman</span>
                        <strong>{{ $lahan->usia_tanaman }} Bulan</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fa-solid fa-leaf text-success mb-3" style="font-size: 4rem;"></i>
                <h5 class="fw-bold">Sistem Pengelolaan Perkebunan</h5>
                <p class="text-muted">Nyapah Banyu Plantation</p>
            </div>
        </div>
    </div>
</div>
@endsection
