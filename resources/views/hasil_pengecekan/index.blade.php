@extends('layouts.app')

@section('title', 'Hasil Pengecekan Lahan')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="fw-bold mb-1 text-dark" style="font-family: 'Outfit', sans-serif;">Hasil Pengecekan Lahan</h2>
        <p class="text-muted mb-0">Riwayat laporan pengecekan harian pada blok operasional kebun.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('hasil-pengecekan.create') }}" class="btn btn-success rounded-3 px-4 shadow-sm" style="background-color: #059669; border-color: #059669;">
            <i class="fa-solid fa-plus me-1"></i> Input Hasil Pengecekan
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="background-color: #d1fae5; color: #065f46;">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 1px;">Tanggal</th>
                        <th class="text-uppercase text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 1px;">Area Kebun</th>
                        <th class="text-uppercase text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 1px;">Penginput</th>
                        <th class="text-uppercase text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 1px;">Aktivitas</th>
                        <th class="text-uppercase text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 1px;">Peralatan & Perlengkapan</th>
                        <th class="text-uppercase text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 1px;">Penyulaman Bibit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasilPengecekan as $pengecekan)
                        <tr>
                            <td class="px-4">
                                <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($pengecekan->tanggal)->locale('id')->isoFormat('DD MMM YYYY') }}</span>
                            </td>
                            <td class="px-4">
                                <span class="fw-bold text-success">{{ $pengecekan->areaKebun->nama_area ?? '-' }}</span>
                            </td>
                            <td class="px-4">
                                {{ $pengecekan->pengguna->nama_pengguna ?? '-' }}
                            </td>
                            <td class="px-4">
                                <span class="badge" style="background-color: #e2e8f0; color: #334155;">{{ $pengecekan->aktivitas }}</span>
                            </td>
                            <td class="px-4 text-muted" style="font-size: 0.85rem;">
                                <div><i class="fa-solid fa-wrench me-1"></i> {{ $pengecekan->peralatan->nama_peralatan ?? '-' }}</div>
                                <div><i class="fa-solid fa-box-archive me-1"></i> {{ $pengecekan->perlengkapan->nama_perlengkapan ?? '-' }}</div>
                            </td>
                            <td class="px-4">
                                @if($pengecekan->id_komoditas_bibit)
                                    <div class="fw-semibold text-dark">{{ $pengecekan->komoditasBibit->nama_komoditas ?? '-' }}</div>
                                    <small class="text-muted">{{ $pengecekan->jumlah_bibit }} Bibit</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-clipboard-check mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="mb-0">Belum ada riwayat hasil pengecekan yang dilaporkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($hasilPengecekan->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-end">
            {{ $hasilPengecekan->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
