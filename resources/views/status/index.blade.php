@extends('layouts.app')

@section('title', 'Master Data Status')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Master Data Status</h2>
        <p class="text-muted m-0">Kelola master data status untuk berbagai operasional kebun</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('status.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Tambah Status
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- KOTAK PENCARIAN -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('status.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0" placeholder="Cari nama status..." value="{{ request('cari') }}">
                </div>
            </div>
            
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('status.index') }}" class="btn btn-outline-secondary px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL DATA STATUS -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle m-0" style="min-width: 600px;">
                <thead class="table-success" style="background-color: var(--mint-green);">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">No</th>
                        <th class="py-3">Nama Status</th>
                        <th class="py-3">Keterangan</th>
                        <th class="py-3 text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataStatus as $index => $status)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $dataStatus->firstItem() + $index }}</td>
                            <td class="fw-semibold text-success">
                                <i class="fa-solid fa-tags me-1 text-muted"></i> {{ $status->nama_status }}
                            </td>
                            <td>{{ $status->keterangan ?? '-' }}</td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('status.edit', $status->id_status) }}" class="btn btn-sm btn-outline-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <form id="hapus-form-{{ $status->id_status }}" action="{{ route('status.destroy', $status->id_status) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="konfirmasiHapus({{ $status->id_status }}, '{{ $status->nama_status }}')" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-tags mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="m-0">Belum ada master data status yang didaftarkan.</p>
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
        Menampilkan {{ $dataStatus->firstItem() ?? 0 }} s.d. {{ $dataStatus->lastItem() ?? 0 }} dari total {{ $dataStatus->total() }} data status.
    </div>
    <div>
        {{ $dataStatus->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Data Status?',
            text: "Apakah Anda yakin ingin menghapus status \"" + nama + "\"?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
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
