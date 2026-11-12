@extends('layouts.app')

@section('title', 'Operasional Kebun (Perawatan)')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Operasional Kebun (Perawatan)</h2>
        <p class="text-muted m-0">Catat dan pantau aktivitas perawatan lahan seperti pemupukan dan obat hama</p>
    </div>
    <div class="col-auto">
        @if(!Auth::user()->isOwner())
        <a href="{{ route('operasional.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Catat Perawatan Baru
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

<!-- KOTAK PENCARIAN & FILTER -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('operasional.index') }}" class="row g-3">
            <div class="col-md-9">
                <select name="jenis_perawatan" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis Perawatan --</option>
                    <option value="Pemupukan" {{ request('jenis_perawatan') == 'Pemupukan' ? 'selected' : '' }}>Pemupukan</option>
                    <option value="Obat Hama" {{ request('jenis_perawatan') == 'Obat Hama' ? 'selected' : '' }}>Pemberian Obat Hama</option>
                    <option value="Pemupukan dan Obat Hama" {{ request('jenis_perawatan') == 'Pemupukan dan Obat Hama' ? 'selected' : '' }}>Pemupukan & Obat Hama</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Saring
                </button>
                @if(request('jenis_perawatan'))
                    <a href="{{ route('operasional.index') }}" class="btn btn-light border px-3">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABEL RIWAYAT PERAWATAN -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0" style="min-width: 900px;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 py-3 text-muted" style="width: 60px;">No</th>
                        <th class="py-3 text-muted">Tanggal</th>
                        <th class="py-3 text-muted">Area / Lahan</th>
                        <th class="py-3 text-muted">Jenis Perawatan</th>
                        <th class="py-3 text-muted">Pegawai Pelaksana</th>
                        <th class="py-3 text-muted">Peralatan / Perlengkapan</th>
                        <th class="py-3 text-muted text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataOperasional as $index => $ops)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $dataOperasional->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ date('d-m-Y', strtotime($ops->tanggal_perawatan)) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ optional($ops->area)->nama_area ?? '-' }}</div>
                                <small class="text-muted">{{ optional($ops->komoditas)->nama_komoditas ?? '-' }}</small>
                            </td>
                            <td>
                                @if($ops->jenis_perawatan == 'Pemupukan')
                                    <span class="badge bg-success text-white"><i class="fa-solid fa-seedling me-1"></i> Pemupukan</span>
                                @elseif($ops->jenis_perawatan == 'Obat Hama')
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-bug-slash me-1"></i> Obat Hama</span>
                                @else
                                    <span class="badge bg-primary text-white"><i class="fa-solid fa-layer-group me-1"></i> Keduanya</span>
                                @endif
                                
                                @if($ops->keterangan)
                                    <br><small class="text-muted mt-1 d-inline-block">{{ $ops->keterangan }}</small>
                                @endif
                            </td>
                            <td>{{ optional($ops->pegawai)->nama_pegawai ?? '-' }}</td>
                            <td>
                                <div class="small">
                                                <div><i class="fa-solid fa-toolbox text-muted me-1"></i> <small>Peralatan: {{ $ops->nama_peralatan }}</small></div>
                                                <div><i class="fa-solid fa-box-open text-muted me-1"></i> <small>Perlengkapan: {{ $ops->nama_perlengkapan }}</small></div>
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                @if(!Auth::user()->isOwner())
                                <div class="btn-group" role="group">
                                    <a href="{{ route('operasional.edit', $ops->id_perawatan) }}" class="btn btn-sm btn-light border text-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form id="hapus-form-{{ $ops->id_perawatan }}" action="{{ route('operasional.destroy', $ops->id_perawatan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border text-danger" onclick="konfirmasiHapus({{ $ops->id_perawatan }})" title="Hapus Data">
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
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-clipboard-check mb-3" style="font-size: 3rem; color: #e2e8f0;"></i>
                                <p class="m-0">Belum ada riwayat aktivitas perawatan yang dicatat.</p>
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
        Menampilkan {{ $dataOperasional->firstItem() ?? 0 }} s.d. {{ $dataOperasional->lastItem() ?? 0 }} dari total {{ $dataOperasional->total() }} riwayat perawatan.
    </div>
    <div>
        {{ $dataOperasional->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Riwayat Perawatan?',
            text: "Apakah Anda yakin ingin menghapus catatan perawatan ini?",
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
