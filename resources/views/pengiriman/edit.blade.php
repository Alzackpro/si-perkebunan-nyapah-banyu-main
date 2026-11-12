@extends('layouts.app')

@section('title', 'Ubah Pengiriman / Penjualan')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('pengiriman.index') }}" class="text-decoration-none text-success">Pengiriman Pabrik</a> 
            <span class="text-muted fs-4">/ Ubah Pengiriman</span>
        </h2>
        <p class="text-muted m-0">Perbarui catatan pengiriman dan penjualan panen</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('pengiriman.update', $pengiriman->id_pengiriman) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- BARIS 1: PANEN & STATUS -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_panen" class="form-label fw-bold text-dark">SUMBER PANEN (Telah Disetujui/Terjual)</label>
                    <select name="id_panen" id="id_panen" class="form-select bg-light" required>
                        <option value="">-- Pilih Hasil Panen --</option>
                        @forelse($dataPanen as $panen)
                            <option value="{{ $panen->id_panen }}" data-netto="{{ $panen->netto_panen }}" {{ old('id_panen', $pengiriman->id_panen) == $panen->id_panen ? 'selected' : '' }}>
                                {{ date('d M Y', strtotime($panen->tanggal)) }} | {{ optional($panen->komoditas)->nama_komoditas }} | Netto: {{ $panen->netto_panen }} Kg
                            </option>
                        @empty
                            <option value="" disabled>Belum ada panen yang disetujui (Status Terjual)</option>
                        @endforelse
                    </select>
                    @error('id_panen')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="id_status" class="form-label fw-bold text-dark">STATUS PENGIRIMAN</label>
                    <select name="id_status" id="id_status" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        @foreach($statusList as $status)
                            <option value="{{ $status->id_status }}" {{ $status->id_status == 3 ? 'selected' : '' }}>
                                {{ $status->nama_status }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- BARIS 2: JADWAL & KOMODITAS -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="id_jadwal" class="form-label fw-bold text-dark">JADWAL KEGIATAN</label>
                    <select name="id_jadwal" id="id_jadwal" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        @foreach($jadwal as $jdw)
                            <option value="{{ $jdw->id_jadwal }}" {{ old('id_jadwal', $pengiriman->id_jadwal) == $jdw->id_jadwal ? 'selected' : '' }}>
                                {{ $jdw->jenis_kegiatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="id_komoditas" class="form-label fw-bold text-dark">KOMODITAS</label>
                    <select name="id_komoditas" id="id_komoditas" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                        @foreach($komoditas as $kom)
                            <option value="{{ $kom->id_komoditas }}" {{ old('id_komoditas', $pengiriman->id_komoditas) == $kom->id_komoditas ? 'selected' : '' }}>
                                {{ $kom->nama_komoditas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="text-muted mb-4">

            <!-- BARIS 3: TANGGAL & KETERANGAN -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="tanggal_pengiriman" class="form-label fw-bold text-dark">TANGGAL PENGIRIMAN</label>
                    <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman" class="form-control @error('tanggal_pengiriman') is-invalid @enderror" value="{{ old('tanggal_pengiriman', $pengiriman->tanggal_pengiriman) }}" required>
                    @error('tanggal_pengiriman')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN PABRIK (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="1" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Contoh: Tujuan PT. ABC">{{ old('keterangan', $pengiriman->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- BARIS 4: PERHITUNGAN PENJUALAN -->
            <div class="row mb-4 p-4 rounded border bg-light">
                <div class="col-12 mb-2">
                    <label class="form-label fw-bold text-dark m-0">RINCIAN PENJUALAN / PENGIRIMAN</label>
                    <p class="text-muted small m-0 mb-3">Total penjualan akan dihitung otomatis: (Jumlah Dikirim x Harga Jual).</p>
                </div>
                
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="jumlah_dikirim" class="form-label fw-bold text-secondary">JUMLAH DIKIRIM (Kg)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="jumlah_dikirim" id="jumlah_dikirim" class="form-control form-control-lg @error('jumlah_dikirim') is-invalid @enderror" value="{{ old('jumlah_dikirim', $pengiriman->jumlah_dikirim) }}" required>
                        <span class="input-group-text bg-white fw-bold">Kg</span>
                    </div>
                    @error('jumlah_dikirim')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="harga_jual" class="form-label fw-bold text-secondary">HARGA JUAL (Per Kg)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white fw-bold">Rp</span>
                        <input type="number" step="0.01" name="harga_jual" id="harga_jual" class="form-control form-control-lg @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', $pengiriman->harga_jual) }}" required>
                    </div>
                    @error('harga_jual')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="text-muted">

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('pengiriman.index') }}" class="btn btn-light px-4 border">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-1"></i> Perbarui Pengiriman
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const panenSelect = document.getElementById('id_panen');
    const jumlahDikirimInput = document.getElementById('jumlah_dikirim');

    panenSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const netto = selectedOption.getAttribute('data-netto');
            if (netto) {
                // If the user hasn't typed anything else, auto-fill it
                jumlahDikirimInput.value = netto;
            }
        } else {
            jumlahDikirimInput.value = '';
        }
    });
});
</script>
@endsection
