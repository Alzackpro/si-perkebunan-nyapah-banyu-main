@extends('layouts.app')

@section('title', 'Master Data User')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Master Data User</h2>
        <p class="text-muted m-0">Kelola akun-akun pengguna yang dapat mengakses sistem</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('user.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Tambah Akun Baru
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
        <form method="GET" action="{{ route('user.index') }}" class="row g-3">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0" placeholder="Cari username, email, atau role..." value="{{ request('cari') }}">
                </div>
            </div>
            
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL DATA USER -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle m-0" style="min-width: 800px;">
                <thead class="table-success" style="background-color: var(--mint-green);">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">No</th>
                        <th class="py-3">Username</th>
                        <th class="py-3">Alamat Email</th>
                        <th class="py-3">Hak Akses (Role)</th>
                        <th class="py-3 text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataUser as $index => $user)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $dataUser->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $user->username }}
                                @if(Auth::id() == $user->id_user)
                                    <span class="badge bg-primary ms-1" style="font-size: 0.65rem;">Anda</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if(strtolower($user->role) == 'owner')
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-crown me-1"></i> Owner</span>
                                @else
                                    <span class="badge bg-info text-dark"><i class="fa-solid fa-user-gear me-1"></i> Pengurus</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('user.edit', $user->id_user) }}" class="btn btn-sm btn-outline-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    @if(Auth::id() != $user->id_user)
                                    <form id="hapus-form-{{ $user->id_user }}" action="{{ route('user.destroy', $user->id_user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="konfirmasiHapus({{ $user->id_user }}, '{{ $user->username }}')" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-user-lock mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="m-0">Belum ada data akun yang terdaftar di sistem.</p>
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
        Menampilkan {{ $dataUser->firstItem() ?? 0 }} s.d. {{ $dataUser->lastItem() ?? 0 }} dari total {{ $dataUser->total() }} data akun.
    </div>
    <div>
        {{ $dataUser->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Data Akun?',
            text: "Apakah Anda yakin ingin menghapus akun \"" + nama + "\"? Akun ini tidak akan bisa login lagi!",
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
