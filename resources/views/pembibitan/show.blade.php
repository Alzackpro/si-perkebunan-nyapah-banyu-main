@extends('layouts.app')

@section('title', 'Detail Data Pembibitan')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Detail Data Pembibitan</h2>
        <p class="text-muted m-0">Rincian lengkap semaian bibit ID #{{ $bibit->id }}</p>
    </div>
    <div class="col-auto d-flex gap-2">
        <a href="{{ route('pembibitan.edit', $bibit->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square me-1"></i> Ubah
        </a>
        <a href="{{ route('pembibitan.index') }}" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fa-solid fa-info-circle me-1"></i> Informasi Lengkap Pembibitan
            </div>
            <div class="card-body p-4">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr>
                            <td class="fw-semibold text-muted" style="width: 30%;">ID Catatan</td>
                            <td class="fw-bold">#{{ $bibit->id }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Jenis Komoditas Bibit</td>
                            <td>
                                @if($bibit->jenis_bibit == 'kopi')
                                    <span class="badge bg-warning text-dark" style="font-size: 0.9rem;"><i class="fa-solid fa-mug-hot me-1"></i> Kopi</span>
                                @elseif($bibit->jenis_bibit == 'sawit')
                                    <span class="badge bg-success text-white" style="font-size: 0.9rem;"><i class="fa-solid fa-tree me-1"></i> Kelapa Sawit</span>
                                @else
                                    <span class="badge bg-info text-dark" style="font-size: 0.9rem;"><i class="fa-solid fa-pepper-hot me-1"></i> Lada</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Jumlah Bibit</td>
                            <td class="fw-bold text-success" style="font-size: 1.1rem;">
                                {{ number_format($bibit->jumlah_bibit, 0, ',', '.') }} <span style="font-weight: 500; font-size: 0.9rem; color: #6c757d;">Pcs</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Tanggal Semai</td>
                            <td>
                                <i class="fa-regular fa-calendar me-2 text-success"></i>
                                {{ \Carbon\Carbon::parse($bibit->tanggal_semai)->locale('id')->isoFormat('DD MMMM YYYY') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Kondisi Bibit</td>
                            <td>
                                @if($bibit->kondisi_bibit == 'sangat_baik')
                                    <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Sangat Baik</span>
                                @elseif($bibit->kondisi_bibit == 'baik')
                                    <span class="badge bg-primary"><i class="fa-solid fa-thumbs-up me-1"></i> Baik</span>
                                @else
                                    <span class="badge bg-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> Kurang Baik</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Lokasi Pembibitan</td>
                            <td>
                                <i class="fa-solid fa-location-dot me-2 text-danger"></i>
                                {{ $bibit->lokasi_pembibitan }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Tanggal Dicatat</td>
                            <td>{{ $bibit->created_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Terakhir Diperbarui</td>
                            <td>{{ $bibit->updated_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
