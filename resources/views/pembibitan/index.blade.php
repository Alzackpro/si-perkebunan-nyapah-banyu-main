@extends('layouts.app')

@section('title', 'Aktivitas Pembibitan')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Aktivitas Pembibitan</h2>
        <p class="text-muted m-0">Ringkasan stok bibit dan riwayat aktivitas pengadaan bibit</p>
    </div>
    <div class="col-auto">
        @if(!Auth::user()->isOwner())
        <a href="{{ route('pembibitan.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Catat Aktivitas Baru
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
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body p-3 p-xl-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-normal mb-1">Stok Saat Ini</h6>
                    <h3 class="fw-bold m-0">{{ number_format($sisaStok, 0, ',', '.') }} <span class="fs-6 fw-normal">Bibit</span></h3>
                </div>
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; opacity: 0.9;">
                    <i class="fa-solid fa-leaf fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body p-3 p-xl-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-normal mb-1">Siap Tanam</h6>
                    <h3 class="fw-bold m-0">{{ number_format($bibitSiapTanam, 0, ',', '.') }} <span class="fs-6 fw-normal">Bibit</span></h3>
                </div>
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; opacity: 0.9;">
                    <i class="fa-solid fa-circle-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100">
            <div class="card-body p-3 p-xl-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-normal mb-1">Belum Siap (Perawatan)</h6>
                    <h3 class="fw-bold m-0">{{ number_format($bibitBelumSiap, 0, ',', '.') }} <span class="fs-6 fw-normal">Bibit</span></h3>
                </div>
                <div class="bg-white text-warning rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; opacity: 0.9;">
                    <i class="fa-solid fa-clock fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body p-3 p-xl-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-normal mb-1">Total Bibit Masuk</h6>
                    <h3 class="fw-bold m-0">{{ number_format($totalBibitMasuk, 0, ',', '.') }} <span class="fs-6 fw-normal">Bibit</span></h3>
                </div>
                <div class="bg-white text-info rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; opacity: 0.9;">
                    <i class="fa-solid fa-seedling fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KOTAK PENCARIAN & FILTER -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('pembibitan.index') }}" class="row g-3">
            <div class="col-md-9">
                <input type="text" name="cari" class="form-control" placeholder="Cari berdasarkan nama pegawai atau keterangan..." value="{{ request('cari') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-success w-100">
                    <i class="fa-solid fa-filter me-1"></i> Saring
                </button>
                @if(request('jenis_aktivitas'))
                    <a href="{{ route('pembibitan.index') }}" class="btn btn-light border px-3">
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
                        <th class="py-3 text-muted">Tanggal</th>
                        <th class="py-3 text-muted">Pegawai</th>
                        <th class="py-3 text-muted">Komoditas</th>
                        <th class="py-3 text-muted">Jenis Aktivitas</th>
                        <th class="py-3 text-muted text-end">Perubahan Stok</th>
                        <th class="py-3 text-muted text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataBibit as $index => $bibit)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $dataBibit->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ date('d-m-Y', strtotime($bibit->tanggal_pembibitan)) }}</div>
                                <small class="text-muted">{{ optional($bibit->jadwal)->jenis_kegiatan ?? '-' }}</small>
                            </td>
                            <td>{{ optional($bibit->pegawai)->nama_pegawai ?? '-' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ optional($bibit->komoditas)->nama_komoditas ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($bibit->jumlah_bibit > 0)
                                    <span class="text-success fw-bold"><i class="fa-solid fa-plus-circle me-1"></i> Pengadaan Bibit</span>
                                    
                                    @php
                                        $targetBulan = ($bibit->id_komoditas == 2) ? 12 : 6;
                                        $usiaAwal = (int) $bibit->usia_bibit;
                                        $bulanBerlalu = \Carbon\Carbon::parse($bibit->tanggal_pembibitan)->diffInMonths(\Carbon\Carbon::now());
                                        $usiaSekarang = (int) floor($usiaAwal + $bulanBerlalu);
                                    @endphp
                                    <div class="mt-1">
                                        @if($usiaSekarang >= $targetBulan)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="fa-solid fa-check"></i> Siap Tanam</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i class="fa-solid fa-clock"></i> Belum Siap ({{ $usiaSekarang }} Bulan)</span>
                                        @endif
                                    </div>

                                @else
                                    <span class="text-muted">Lainnya</span>
                                @endif
                                
                                @if($bibit->keterangan)
                                    <br><small class="text-muted">{{ $bibit->keterangan }}</small>
                                @endif
                            </td>
                            <td class="text-end fw-bold">
                                @if($bibit->jumlah_bibit > 0)
                                    <span class="text-success">+{{ number_format($bibit->jumlah_bibit, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if(!Auth::user()->isOwner())
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pembibitan.edit', $bibit->id_pembibitan) }}" class="btn btn-sm btn-light border text-primary" title="Ubah Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form id="hapus-form-{{ $bibit->id_pembibitan }}" action="{{ route('pembibitan.destroy', $bibit->id_pembibitan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border text-danger" onclick="konfirmasiHapus({{ $bibit->id_pembibitan }})" title="Hapus Data">
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
                                <i class="fa-solid fa-clipboard-list mb-3" style="font-size: 3rem; color: #e2e8f0;"></i>
                                <p class="m-0">Belum ada riwayat aktivitas yang dicatat.</p>
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
        Menampilkan {{ $dataBibit->firstItem() ?? 0 }} s.d. {{ $dataBibit->lastItem() ?? 0 }} dari total {{ $dataBibit->total() }} riwayat aktivitas.
    </div>
    <div>
        {{ $dataBibit->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Riwayat Aktivitas?',
            text: "Apakah Anda yakin ingin menghapus catatan aktivitas ini? Stok bibit akan menyesuaikan kembali.",
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
