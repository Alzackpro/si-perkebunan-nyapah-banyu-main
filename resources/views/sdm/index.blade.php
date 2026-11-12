@extends('layouts.app')

@section('title', 'Operasional SDM / Upah Pekerja')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Operasional SDM / Gaji</h2>
        <p class="text-muted m-0">Catat dan pantau pengeluaran upah harian tenaga kerja lepas</p>
    </div>
    <div class="col-auto">
        @if(!Auth::user()->isOwner())
        <a href="{{ route('sdm.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Catat Pembayaran Upah
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

<!-- KARTU RINGKASAN (Khusus Pengurus) -->
@if(Auth::user()->isPengurus())
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-normal mb-1">Total Pengeluaran Upah Area Anda</h5>
                    <h2 class="fw-bold m-0"><span class="fs-5 fw-normal">Rp</span> {{ number_format($totalUpah, 0, ',', '.') }}</h2>
                </div>
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px; opacity: 0.9;">
                    <i class="fa-solid fa-money-bill-wave fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- KOTAK PENCARIAN & FILTER -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('sdm.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0" placeholder="Cari nama pekerja, jenis pekerjaan, atau keterangan..." value="{{ request('cari') }}">
                </div>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('sdm.index') }}" class="btn btn-light border px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL RIWAYAT UPAH PEKERJA -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0" style="min-width: 900px;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 py-3 text-muted" style="width: 60px;">No</th>
                        <th class="py-3 text-muted">Tanggal Bayar</th>
                        <th class="py-3 text-muted">Nama Pekerja</th>
                        <th class="py-3 text-muted">Jenis Pekerjaan</th>
                        <th class="py-3 text-muted text-center">Jml Hari</th>
                        <th class="py-3 text-muted text-end">Total Upah (Rp)</th>
                        <th class="py-3 text-muted text-center">Bukti</th>
                        <th class="py-3 text-muted text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataSdm as $index => $sdm)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $dataSdm->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ date('d-m-Y', strtotime($sdm->tanggal_bayar)) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $sdm->nama_pekerja }}</div>
                                <small class="text-muted">{{ optional($sdm->area)->nama_area ?? '-' }} ({{ optional($sdm->komoditas)->nama_komoditas ?? '-' }})</small>
                            </td>
                            <td>
                                @if($sdm->jenis_pekerjaan == 'Panen')
                                    <span class="badge bg-success"><i class="fa-solid fa-wheat-awn me-1"></i> Panen</span>
                                @elseif($sdm->jenis_pekerjaan == 'Pemupukan')
                                    <span class="badge bg-primary"><i class="fa-solid fa-seedling me-1"></i> Pemupukan</span>
                                @elseif($sdm->jenis_pekerjaan == 'Obat Hama')
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-bug-slash me-1"></i> Obat Hama</span>
                                @else
                                    <span class="badge bg-info text-dark"><i class="fa-solid fa-user-gear me-1"></i> {{ $sdm->jenis_pekerjaan }}</span>
                                @endif
                                
                                @if($sdm->keterangan)
                                    <br><small class="text-muted d-block mt-1">{{ $sdm->keterangan }}</small>
                                @endif
                            </td>
                            <td class="text-center fw-bold text-secondary">{{ $sdm->jumlah_hari }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($sdm->total_upah, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($sdm->bukti_pembayaran)
                                    <a href="{{ asset($sdm->bukti_pembayaran) }}" target="_blank" class="badge bg-light text-primary border text-decoration-none">
                                        <i class="fa-solid fa-image me-1"></i> Foto
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if(!Auth::user()->isOwner())
                                <div class="btn-group" role="group">
                                    <a href="{{ route('sdm.edit', $sdm->id_upah) }}" class="btn btn-sm btn-light border text-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form id="hapus-form-{{ $sdm->id_upah }}" action="{{ route('sdm.destroy', $sdm->id_upah) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border text-danger" onclick="konfirmasiHapus({{ $sdm->id_upah }})" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                    <span class="badge bg-light text-secondary border"><i class="fa-solid fa-eye me-1"></i> Pantau</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-people-carry-box mb-3" style="font-size: 3rem; color: #e2e8f0;"></i>
                                <p class="m-0">Belum ada riwayat pembayaran upah pekerja yang dicatat.</p>
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
        Menampilkan {{ $dataSdm->firstItem() ?? 0 }} s.d. {{ $dataSdm->lastItem() ?? 0 }} dari total {{ $dataSdm->total() }} riwayat pembayaran.
    </div>
    <div>
        {{ $dataSdm->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Riwayat Upah?',
            text: "Apakah Anda yakin ingin menghapus catatan pembayaran ini? Foto bukti (jika ada) juga akan ikut terhapus.",
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
