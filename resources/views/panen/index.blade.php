@extends('layouts.app')

@section('title', 'Transaksi Hasil Panen')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Transaksi Hasil Panen</h2>
        <p class="text-muted m-0">Catat dan pantau hasil panen harian, kotor dan bersih</p>
    </div>
    <div class="col-auto">
        @if(!Auth::user()->isOwner())
        <a href="{{ route('panen.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Catat Hasil Panen Baru
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
                    <h5 class="fw-normal mb-1">Total Panen Bersih (Netto) Anda</h5>
                    <h2 class="fw-bold m-0">{{ number_format($totalNetto, 2, ',', '.') }} <span class="fs-5 fw-normal">Kg</span></h2>
                </div>
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px; opacity: 0.9;">
                    <i class="fa-solid fa-scale-balanced fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-secondary text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-normal mb-1">Total Panen Kotor (Gross) Anda</h5>
                    <h2 class="fw-bold m-0">{{ number_format($totalGross, 2, ',', '.') }} <span class="fs-5 fw-normal">Kg</span></h2>
                </div>
                <div class="bg-white text-secondary rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px; opacity: 0.9;">
                    <i class="fa-solid fa-weight-hanging fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- KOTAK PENCARIAN & FILTER -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('panen.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0" placeholder="Cari keterangan / catatan..." value="{{ request('cari') }}">
                </div>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('panen.index') }}" class="btn btn-light border px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL RIWAYAT PANEN -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0" style="min-width: 900px;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 py-3 text-muted" style="width: 60px;">No</th>
                        <th class="py-3 text-muted">Tanggal Panen</th>
                        <th class="py-3 text-muted">Area & Komoditas</th>
                        <th class="py-3 text-muted text-end">Gross (Kotor)</th>
                        <th class="py-3 text-muted text-end">Netto (Bersih)</th>
                        <th class="py-3 text-muted text-center">Status</th>
                        <th class="py-3 text-muted text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataPanen as $index => $panen)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $dataPanen->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ date('d M Y', strtotime($panen->tanggal)) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ optional($panen->area)->nama_area ?? '-' }}</div>
                                {!! $panen->badge_jenis_tanaman !!}
                            </td>
                            <td class="text-end text-muted">{{ number_format($panen->gross_panen, 2, ',', '.') }} Kg</td>
                            <td class="text-end fw-bold text-success">{{ number_format($panen->netto_panen, 2, ',', '.') }} Kg</td>
                            <td class="text-center">
                                @if(optional($panen->status_panen)->nama_status == 'Disimpan')
                                    <span class="badge bg-secondary"><i class="fa-solid fa-warehouse me-1"></i> {{ $panen->status_panen->nama_status }}</span>
                                @elseif(optional($panen->status_panen)->nama_status == 'terkirim')
                                    <span class="badge bg-info text-dark"><i class="fa-solid fa-truck me-1"></i> Terkirim Pabrik</span>
                                @elseif(optional($panen->status_panen)->nama_status == 'Dijual')
                                    <span class="badge bg-success"><i class="fa-solid fa-money-bill-wave me-1"></i> Terjual</span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ optional($panen->status_panen)->nama_status ?? 'Draft' }}</span>
                                @endif
                                
                                @if($panen->keterangan)
                                    <br><small class="text-muted d-block mt-1">{{ $panen->keterangan }}</small>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if(!Auth::user()->isOwner())
                                <div class="btn-group" role="group">
                                    <a href="{{ route('panen.edit', $panen->id_panen) }}" class="btn btn-sm btn-light border text-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form id="hapus-form-{{ $panen->id_panen }}" action="{{ route('panen.destroy', $panen->id_panen) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border text-danger" onclick="konfirmasiHapus({{ $panen->id_panen }})" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-pen"></i> Ubah Status
                                    </button>
                                    <ul class="dropdown-menu shadow border-0">
                                        @foreach($listStatus as $status)
                                        <li>
                                            <form action="{{ route('panen.updateStatus', $panen->id_panen) }}" method="POST">
                                                @csrf @method('PATCH') 
                                                <input type="hidden" name="id_status" value="{{ $status->id_status }}">
                                                <button class="dropdown-item fw-bold" type="submit">
                                                    @if(strtolower($status->nama_status) == 'disimpan')
                                                        <i class="fa-solid fa-warehouse me-2 text-secondary"></i>
                                                    @elseif(strtolower($status->nama_status) == 'terkirim')
                                                        <i class="fa-solid fa-truck me-2 text-info"></i>
                                                    @elseif(strtolower($status->nama_status) == 'dijual')
                                                        <i class="fa-solid fa-money-bill-wave me-2 text-success"></i>
                                                    @endif
                                                    {{ $status->nama_status }}
                                                </button>
                                            </form>
                                        </li>
                                        @endforeach
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form id="hapus-form-{{ $panen->id_panen }}" action="{{ route('panen.destroy', $panen->id_panen) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item text-danger" type="button" onclick="konfirmasiHapus({{ $panen->id_panen }})">
                                                    <i class="fa-solid fa-trash me-2"></i> Hapus Data
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-seedling mb-3" style="font-size: 3rem; color: #e2e8f0;"></i>
                                <p class="m-0">Belum ada riwayat hasil panen yang dicatat.</p>
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
        Menampilkan {{ $dataPanen->firstItem() ?? 0 }} s.d. {{ $dataPanen->lastItem() ?? 0 }} dari total {{ $dataPanen->total() }} riwayat panen.
    </div>
    <div>
        {{ $dataPanen->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Riwayat Panen?',
            text: "Apakah Anda yakin ingin menghapus catatan hasil panen ini?",
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
