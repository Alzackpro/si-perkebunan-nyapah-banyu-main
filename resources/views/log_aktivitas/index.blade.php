@extends('layouts.app')

@section('title', 'Audit Log Aktivitas')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Audit Log Aktivitas</h2>
        <p class="text-muted m-0">Riwayat lengkap aktivitas dan transaksi data yang dilakukan oleh pengguna dalam sistem</p>
    </div>
</div>

<!-- TABEL LOG AKTIVITAS -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <i class="fa-solid fa-clock-rotate-left me-1"></i> Audit Trail Sistem
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle m-0" style="min-width: 800px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">No</th>
                        <th class="py-3" style="width: 180px;">Waktu & Tanggal</th>
                        <th class="py-3" style="width: 180px;">Pengguna (User)</th>
                        <th class="py-3" style="width: 200px;">Aktivitas</th>
                        <th class="py-3">Deskripsi Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logAktivitas as $index => $log)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $logAktivitas->firstItem() + $index }}</td>
                            <td>
                                <span class="text-muted"><i class="fa-regular fa-clock me-1 text-success"></i> {{ $log->created_at->format('d-m-Y H:i:s') }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $log->user->name }}</span>
                                <span class="badge bg-light text-success border border-success ms-1 small text-uppercase" style="font-size: 0.72rem;">
                                    {{ $log->user->role == 'owner' ? 'Pemilik' : 'Pengurus' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.85rem;">
                                    {{ $log->aktivitas }}
                                </span>
                            </td>
                            <td class="text-dark">{{ str_replace('Owner ', 'Pemilik ', $log->deskripsi) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-clock mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="m-0">Belum ada catatan aktivitas di sistem.</p>
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
        Menampilkan {{ $logAktivitas->firstItem() ?? 0 }} s.d. {{ $logAktivitas->lastItem() ?? 0 }} dari total {{ $logAktivitas->total() }} catatan log.
    </div>
    <div>
        {{ $logAktivitas->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
