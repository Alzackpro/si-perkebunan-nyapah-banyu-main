@extends('layouts.app')

@section('title', 'Detail Operasional Lahan')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Detail Operasional Lahan</h2>
        <p class="text-muted m-0">Detail informasi operasional ID: <strong>#{{ $operasional->id }}</strong></p>
    </div>
    <div class="col-auto d-flex gap-2">
        @if(Auth::user()->role == 'owner')
            <a href="{{ route('operasional.edit', $operasional->id) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square me-1"></i> Ubah
            </a>
        @endif
        <a href="{{ route('operasional.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <i class="fa-solid fa-info-circle me-1"></i> Informasi Dasar Operasional
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Area Kebun</label>
                        <h5 class="mb-0">{{ $operasional->areaKebun->nama_lahan ?? '-' }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Jenis Tanaman</label>
                        @if($operasional->jenis_tanaman == 'kopi')
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-mug-hot me-1"></i> Kopi</span>
                        @elseif($operasional->jenis_tanaman == 'sawit')
                            <span class="badge bg-success text-white"><i class="fa-solid fa-tree me-1"></i> Sawit</span>
                        @else
                            <span class="badge bg-info text-dark"><i class="fa-solid fa-pepper-hot me-1"></i> Lada</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Jenis Operasional</label>
                        {!! $operasional->badge_jenis_operasional !!}
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Jadwal Operasional</label>
                        <h5 class="mb-0">{{ date('d-m-Y', strtotime($operasional->jadwal_operasional)) }}</h5>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="fw-semibold text-muted mb-1 d-block">Status Operasional</label>
                        {!! $operasional->badge_status !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <i class="fa-solid fa-list-check me-1"></i> Detail Aktivitas
            </div>
            <div class="card-body">
                <div class="row">
                    @if($operasional->nama_pupuk)
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-muted mb-1 d-block">Nama Pupuk</label>
                            <h5 class="mb-0">{{ $operasional->nama_pupuk }}</h5>
                        </div>
                    @endif
                    @if($operasional->nama_obat_hama)
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-muted mb-1 d-block">Nama Obat Hama</label>
                            <h5 class="mb-0">{{ $operasional->nama_obat_hama }}</h5>
                        </div>
                    @endif
                    @if($operasional->jenis_bibit)
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-muted mb-1 d-block">Jenis Bibit</label>
                            <h5 class="mb-0">{{ $operasional->jenis_bibit }}</h5>
                        </div>
                    @endif
                    @if($operasional->jumlah_bibit)
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-muted mb-1 d-block">Jumlah Bibit</label>
                            <h5 class="mb-0">{{ number_format($operasional->jumlah_bibit, 0, ',', '.') }} Unit</h5>
                        </div>
                    @endif
                    @if($operasional->jumlah_tanaman_mati)
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-muted mb-1 d-block">Jumlah Tanaman Mati</label>
                            <h5 class="mb-0 text-danger">{{ number_format($operasional->jumlah_tanaman_mati, 0, ',', '.') }} Pohon</h5>
                        </div>
                    @endif
                    @if($operasional->jumlah_tanaman_diganti)
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-muted mb-1 d-block">Jumlah Tanaman Diganti</label>
                            <h5 class="mb-0 text-success">{{ number_format($operasional->jumlah_tanaman_diganti, 0, ',', '.') }} Pohon</h5>
                        </div>
                    @endif
                    @if($operasional->biaya_operasional)
                        <div class="col-md-12 mb-3">
                            <label class="fw-semibold text-muted mb-1 d-block">Biaya Operasional</label>
                            <h4 class="mb-0 text-success fw-bold">Rp {{ number_format($operasional->biaya_operasional, 2, ',', '.') }}</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($operasional->bukti_nota)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <i class="fa-solid fa-file-image me-1"></i> Bukti Nota
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $operasional->bukti_nota) }}" alt="Bukti Nota" class="img-fluid rounded" style="max-height: 400px;">
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <i class="fa-solid fa-comments me-1"></i> Keterangan
            </div>
            <div class="card-body">
                @if($operasional->keterangan)
                    <p class="mb-0">{{ $operasional->keterangan }}</p>
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
                <h5 class="mb-0">{{ $operasional->user->name ?? '-' }}</h5>
                <p class="text-muted mb-0">{{ $operasional->user->email ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
