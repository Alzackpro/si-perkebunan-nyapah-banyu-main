@extends('layouts.app')

@section('title', 'Data Pengiriman / Penjualan')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Pengiriman Pabrik & Penjualan</h2>
        <p class="text-muted m-0">Catat dan pantau pengiriman hasil panen yang telah disetujui / terjual</p>
    </div>
    <div class="col-auto">
        @if(!Auth::user()->isOwner())
        <a href="{{ route('pengiriman.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Catat Pengiriman
        </a>
        @endif
    </div>
</div>

@if(session('sukses'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="fa-solid fa-check-circle me-1"></i> {{ session('sukses') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('gagal'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('gagal') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- KARTU RINGKASAN -->
@if(Auth::user()->isPengurus())
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-normal mb-1">Total Nilai Penjualan Anda</h5>
                    <h2 class="fw-bold m-0"><span class="fs-5 fw-normal">Rp</span> {{ number_format($totalPenjualan, 0, ',', '.') }}</h2>
                </div>
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px; opacity: 0.9;">
                    <i class="fa-solid fa-sack-dollar fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- KOTAK PENCARIAN & FILTER -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('pengiriman.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0" placeholder="Cari keterangan pengiriman, nama supir, tujuan pabrik..." value="{{ request('cari') }}">
                </div>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('pengiriman.index') }}" class="btn btn-light border px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL RIWAYAT PENGIRIMAN -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0" style="min-width: 900px;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 py-3 text-muted" style="width: 60px;">No</th>
                        <th class="py-3 text-muted">Tgl Kirim</th>
                        <th class="py-3 text-muted">Komoditas & Sumber Panen</th>
                        <th class="py-3 text-muted text-end">Jumlah (Kg)</th>
                        <th class="py-3 text-muted text-end">Total Jual (Rp)</th>
                        <th class="py-3 text-muted text-center">Status</th>
                        <th class="py-3 text-muted text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataPengiriman as $index => $kirim)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $dataPengiriman->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ date('d-m-Y', strtotime($kirim->tanggal_pengiriman)) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ optional($kirim->komoditas)->nama_komoditas ?? '-' }}</div>
                                <small class="text-muted">Panen Tgl: {{ optional($kirim->panen)->tanggal ? date('d-m-Y', strtotime($kirim->panen->tanggal)) : '-' }}</small>
                            </td>
                            <td class="text-end fw-bold text-secondary">{{ number_format($kirim->jumlah_dikirim, 2, ',', '.') }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($kirim->total_penjualan, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if(optional($kirim->status_pengiriman)->nama_status == 'Terkirim')
                                    <span class="badge bg-info text-dark"><i class="fa-solid fa-truck me-1"></i> Terkirim</span>
                                @elseif(optional($kirim->status_pengiriman)->nama_status == 'Dijual')
                                    <span class="badge bg-success"><i class="fa-solid fa-money-bill-wave me-1"></i> Terjual</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fa-solid fa-clock me-1"></i> {{ optional($kirim->status_pengiriman)->nama_status ?? 'Proses' }}</span>
                                @endif
                                
                                @if($kirim->keterangan)
                                    <br><small class="text-muted d-block mt-1">{{ $kirim->keterangan }}</small>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    @if(!Auth::user()->isOwner())
                                        <a href="{{ route('pengiriman.edit', $kirim->id_pengiriman) }}" class="btn btn-sm btn-light border text-primary" title="Ubah Data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    @else
                                        <form id="hapus-form-{{ $kirim->id_pengiriman }}" action="{{ route('pengiriman.destroy', $kirim->id_pengiriman) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-light border text-danger" onclick="konfirmasiHapus({{ $kirim->id_pengiriman }})" title="Hapus Data">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-truck-ramp-box mb-3" style="font-size: 3rem; color: #e2e8f0;"></i>
                                <p class="m-0">Belum ada riwayat pengiriman hasil panen.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- PAGINASI DATA -->
<div class="mt-4 d-flex justify-content-between align-items-center">
    <div class="text-muted" style="font-size: 0.9rem;">
        Menampilkan {{ $dataPengiriman->firstItem() ?? 0 }} s.d. {{ $dataPengiriman->lastItem() ?? 0 }} dari total {{ $dataPengiriman->total() }} riwayat pengiriman.
    </div>
    <div>
        {{ $dataPengiriman->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Riwayat Pengiriman?',
            text: "Apakah Anda yakin ingin menghapus catatan pengiriman/penjualan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapus-form-' + id).submit();
            }
        });
    }
</script>
@endsection
