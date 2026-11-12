@extends('layouts.app')

@section('title', 'Master Data Perlengkapan')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Master Data Perlengkapan</h2>
        <p class="text-muted m-0">Kelola inventaris perlengkapan tambahan atau alat habis pakai</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('perlengkapan.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Tambah Perlengkapan
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
        <form method="GET" action="{{ route('perlengkapan.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0" placeholder="Cari nama perlengkapan..." value="{{ request('cari') }}">
                </div>
            </div>
            
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('perlengkapan.index') }}" class="btn btn-outline-secondary px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL DATA PERLENGKAPAN -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle m-0" style="min-width: 600px;">
                <thead class="table-success" style="background-color: var(--mint-green);">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">No</th>
                        <th class="py-3">Nama Perlengkapan</th>
                        <th class="py-3">Keterangan</th>
                        <th class="py-3 text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataPerlengkapan as $index => $item)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $dataPerlengkapan->firstItem() + $index }}</td>
                            <td class="fw-semibold text-success">
                                <i class="fa-solid fa-box-open me-1 text-muted"></i> {{ $item->nama_perlengkapan }}
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('perlengkapan.edit', $item->id_perlengkapan) }}" class="btn btn-sm btn-outline-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <form id="hapus-form-{{ $item->id_perlengkapan }}" action="{{ route('perlengkapan.destroy', $item->id_perlengkapan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="konfirmasiHapus({{ $item->id_perlengkapan }}, '{{ $item->nama_perlengkapan }}')" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-boxes-stacked mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="m-0">Belum ada master data perlengkapan yang didaftarkan.</p>
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
        Menampilkan {{ $dataPerlengkapan->firstItem() ?? 0 }} s.d. {{ $dataPerlengkapan->lastItem() ?? 0 }} dari total {{ $dataPerlengkapan->total() }} data perlengkapan.
    </div>
    <div>
        {{ $dataPerlengkapan->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Data Perlengkapan?',
            text: "Apakah Anda yakin ingin menghapus perlengkapan \"" + nama + "\"?",
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
