@extends('layouts.app')

@section('title', 'Detail Data Panen')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Detail Data Panen</h2>
        <p class="text-muted m-0">Detail informasi panen ID: <strong>#{{ $panen->id }}</strong></p>
    </div>
    <div class="col-auto d-flex gap-2">
        @if(Auth::user()->role == 'owner')
            <a href="{{ route('panen.edit', $panen->id) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square me-1"></i> Ubah
            </a>
        @endif
        <a href="{{ route('panen.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <i class="fa-solid fa-info-circle me-1"></i> Informasi Dasar Panen
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Tanggal Panen</label>
                        <h5 class="mb-0">{{ date('d-m-Y', strtotime($panen->tanggal_panen)) }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Jadwal Panen</label>
                        <h5 class="mb-0">{{ $panen->jadwal_panen ?? '-' }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Area Kebun</label>
                        <h5 class="mb-0">{{ $panen->areaKebun->nama_lahan ?? '-' }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Jenis Tanaman</label>
                        {!! $panen->badge_jenis_tanaman !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <i class="fa-solid fa-weight-scale me-1"></i> Data Berat Panen
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Jumlah Panen</label>
                        <h5 class="mb-0">{{ $panen->jumlah_panen ? number_format($panen->jumlah_panen, 2, ',', '.') : '-' }} Unit</h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Gross Panen</label>
                        <h5 class="mb-0">{{ number_format($panen->gross_panen, 2, ',', '.') }} kg</h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Netto Panen</label>
                        <h5 class="mb-0">{{ number_format($panen->netto_panen, 2, ',', '.') }} kg</h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Panen Brondol</label>
                        <h5 class="mb-0">{{ $panen->panen_brondol ? number_format($panen->panen_brondol, 2, ',', '.') : '-' }} kg</h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Harga Jual per kg</label>
                        <h5 class="mb-0">{{ $panen->harga_jual ? 'Rp ' . number_format($panen->harga_jual, 2, ',', '.') : '-' }}</h5>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Total Pendapatan</label>
                        <h5 class="mb-0 text-success fw-bold">{{ $panen->total_pendapatan ? 'Rp ' . number_format($panen->total_pendapatan, 2, ',', '.') : '-' }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <i class="fa-solid fa-comments me-1"></i> Keterangan
            </div>
            <div class="card-body">
                @if($panen->keterangan)
                    <p class="mb-0">{{ $panen->keterangan }}</p>
                @else
                    <p class="text-muted mb-0"><em>Tidak ada keterangan.</em></p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="fa-solid fa-user me-1"></i> Pencatat Data
            </div>
            <div class="card-body text-center">
                <i class="fa-solid fa-user-circle text-secondary mb-3" style="font-size: 4rem;"></i>
                <h5 class="mb-0">{{ $panen->user->name ?? '-' }}</h5>
                <p class="text-muted mb-0">{{ $panen->user->email ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
