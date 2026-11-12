@extends('layouts.app')

@section('title', 'Master Data Pegawai')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Master Data Pegawai</h2>
        <p class="text-muted m-0">Kelola data informasi seluruh pegawai (Owner & Pengurus)</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('pegawai.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Tambah Pegawai Baru
        </a>
    </div>
</div>

<!-- KOTAK PENCARIAN -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('pegawai.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0" placeholder="Cari nama, jabatan, atau nomor telepon..." value="{{ request('cari') }}">
                </div>
            </div>
            
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL DATA PEGAWAI -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle m-0" style="min-width: 900px;">
                <thead class="table-success" style="background-color: var(--mint-green);">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">No</th>
                        <th class="py-3">Nama Pegawai</th>
                        <th class="py-3">Jabatan</th>
                        <th class="py-3">No Telepon</th>
                        <th class="py-3">Alamat</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Akun Taut</th>
                        <th class="py-3 text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataPegawai as $index => $pegawai)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $dataPegawai->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $pegawai->nama_pegawai }}</td>
                            <td>
                                @if(strtolower($pegawai->jabatan) == 'owner')
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-crown me-1"></i> Owner</span>
                                @else
                                    <span class="badge bg-info text-dark"><i class="fa-solid fa-user-tie me-1"></i> {{ $pegawai->jabatan }}</span>
                                @endif
                            </td>
                            <td>{{ $pegawai->no_tlp ?? '-' }}</td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $pegawai->alamat }}">
                                    {{ $pegawai->alamat ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($pegawai->status == 'Aktif')
                                    <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Aktif</span>
                                @else
                                    <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i> Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                @if($pegawai->user)
                                    <span class="badge bg-secondary"><i class="fa-solid fa-at me-1"></i> {{ $pegawai->user->username }}</span>
                                @else
                                    <span class="text-muted fst-italic">Belum Taut</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pegawai.edit', $pegawai->id_pegawai) }}" class="btn btn-sm btn-outline-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <form id="hapus-form-{{ $pegawai->id_pegawai }}" action="{{ route('pegawai.destroy', $pegawai->id_pegawai) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="konfirmasiHapus({{ $pegawai->id_pegawai }}, '{{ $pegawai->nama_pegawai }}')" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="m-0">Belum ada data pegawai yang terdaftar di sistem.</p>
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
        Menampilkan {{ $dataPegawai->firstItem() ?? 0 }} s.d. {{ $dataPegawai->lastItem() ?? 0 }} dari total {{ $dataPegawai->total() }} data pegawai.
    </div>
    <div>
        {{ $dataPegawai->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Data Pegawai?',
            text: "Apakah Anda yakin ingin menghapus data pegawai \"" + nama + "\"? Tindakan ini tidak dapat dibatalkan!",
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
