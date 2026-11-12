@extends('layouts.app')

@section('title', 'Aktivitas Tanam & Penyulaman')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Aktivitas Tanam (Penyulaman)</h2>
        <p class="text-muted m-0">Riwayat penanaman dan penyulaman bibit di area kebun</p>
    </div>
    <div class="col-auto">
        @if(!Auth::user()->isOwner())
        <a href="{{ route('tanam.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Catat Tanam Baru
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

<!-- KARTU RINGKASAN -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-normal mb-1">Total Bibit Ditanam / Disulam</h5>
                    <h2 class="fw-bold m-0">{{ number_format($totalTanamGlobal, 0, ',', '.') }} <span class="fs-5 fw-normal">Pohon</span></h2>
                </div>
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px; opacity: 0.9;">
                    <i class="fa-solid fa-leaf fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-normal mb-1">Total Riwayat Tanam</h5>
                    <h2 class="fw-bold m-0">{{ number_format($totalAktivitas, 0, ',', '.') }} <span class="fs-5 fw-normal">Aktivitas</span></h2>
                </div>
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px; opacity: 0.9;">
                    <i class="fa-solid fa-clock-rotate-left fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KOTAK PENCARIAN & FILTER -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('tanam.index') }}" class="row g-3">
            <div class="col-md-9">
                <input type="text" name="cari" class="form-control" placeholder="Cari berdasarkan nama lahan atau area..." value="{{ request('cari') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('tanam.index') }}" class="btn btn-light border px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL RIWAYAT AKTIVITAS -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0" style="min-width: 900px;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 py-3 text-muted" style="width: 60px;">No</th>
                        <th class="py-3 text-muted">Tanggal Tanam</th>
                        <th class="py-3 text-muted">Area Lahan</th>
                        <th class="py-3 text-muted text-end">Jumlah Tanam</th>
                        <th class="py-3 text-muted">Keterangan</th>
                        <th class="py-3 text-muted text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataTanam as $index => $tanam)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $dataTanam->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ date('d-m-Y', strtotime($tanam->tanggal_tanam)) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ optional($tanam->area)->nama_lahan ?? optional($tanam->area)->nama_area ?? '-' }}</div>
                                <small class="text-muted">{{ optional(optional($tanam->area)->komoditas)->nama_komoditas ?? 'Tanaman' }}</small>
                            </td>
                            <td class="text-end fw-bold">
                                <span class="text-primary">{{ number_format($tanam->jumlah_tanam, 0, ',', '.') }} Pohon</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $tanam->keterangan ?? '-' }}</span>
                            </td>
                            <td class="text-center pe-4">
                                @if(!Auth::user()->isOwner())
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tanam.edit', $tanam->id_tanam) }}" class="btn btn-sm btn-light border text-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form id="hapus-form-{{ $tanam->id_tanam }}" action="{{ route('tanam.destroy', $tanam->id_tanam) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border text-danger" onclick="konfirmasiHapus({{ $tanam->id_tanam }})" title="Hapus Data">
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-seedling fs-1 mb-3 text-light"></i><br>
                                Belum ada riwayat penanaman/penyulaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center">
            <span class="text-muted small">Menampilkan {{ $dataTanam->firstItem() ?? 0 }} sampai {{ $dataTanam->lastItem() ?? 0 }} dari {{ $dataTanam->total() }} riwayat</span>
            {{ $dataTanam->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Riwayat Tanam?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapus-form-' + id).submit();
            }
        })
    }
</script>
@endsection
