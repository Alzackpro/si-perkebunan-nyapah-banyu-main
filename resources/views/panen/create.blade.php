@extends('layouts.app')

@section('title', 'Catat Hasil Panen')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">
            <a href="{{ route('panen.index') }}" class="text-decoration-none text-success">Hasil Panen</a> 
            <span class="text-muted fs-4">/ Catat Baru</span>
        </h2>
        <p class="text-muted m-0">Tambahkan pencatatan hasil timbangan panen kotor dan bersih</p>
    </div>
</div>

<style>
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e9ecef;
        z-index: 1;
        transform: translateY(-50%);
    }
    .step-item {
        position: relative;
        z-index: 2;
        background: white;
        padding: 0 15px;
        text-align: center;
        color: #6c757d;
        font-weight: bold;
    }
    .step-item.active {
        color: #198754;
    }
    .step-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        border: 2px solid #fff;
    }
    .step-item.active .step-circle {
        background-color: #198754;
        color: white;
    }
</style>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        
        <!-- STEP INDICATOR -->
        <div class="step-indicator">
            <div class="step-item active" id="indicator-step-1">
                <div class="step-circle">1</div>
                <div>Data Dasar</div>
            </div>
            <div class="step-item" id="indicator-step-2">
                <div class="step-circle">2</div>
                <div>Upah Pekerja</div>
            </div>
            <div class="step-item" id="indicator-step-3">
                <div class="step-circle">3</div>
                <div>Hasil Panen</div>
            </div>
        </div>

        <form action="{{ route('panen.store') }}" method="POST" id="form-panen">
            @csrf

            <!-- STEP 1: INFO & PERALATAN -->
            <div id="step-1" class="form-step">
                <h4 class="mb-4 text-success fw-bold border-bottom pb-2">Langkah 1: Informasi Dasar</h4>
                
                <!-- BARIS 1: JADWAL & AREA -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_jadwal" class="form-label fw-bold text-dark">JADWAL KEGIATAN</label>
                        <select name="id_jadwal" id="id_jadwal" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                            <option value="">-- Terisi Otomatis --</option>
                            @foreach($jadwal as $jdw)
                                <option value="{{ $jdw->id_jadwal }}" {{ (old('id_jadwal') ?? $defaultJadwal) == $jdw->id_jadwal ? 'selected' : '' }}>
                                    {{ $jdw->jenis_kegiatan }} ({{ optional($jdw->komoditas)->nama_komoditas }} di {{ optional($jdw->area)->nama_area }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_area" class="form-label fw-bold text-dark">AREA LAHAN (YANG SUDAH PRODUKTIF)</label>
                        <select name="id_area" id="id_area" class="form-select @error('id_area') is-invalid @enderror" required>
                            <option value="">-- Pilih Area Lahan --</option>
                            @foreach($area as $lahan)
                                <option value="{{ $lahan->id_area }}" {{ (old('id_area') ?? $defaultArea) == $lahan->id_area ? 'selected' : '' }}>
                                    {{ $lahan->nama_area }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- BARIS 2: KOMODITAS & TANGGAL -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="id_komoditas" class="form-label fw-bold text-dark">KOMODITAS</label>
                        <select name="id_komoditas" id="id_komoditas" class="form-select bg-light" style="pointer-events: none;" tabindex="-1" required>
                            <option value="">-- Terisi Otomatis --</option>
                            @foreach($komoditas as $kom)
                                <option value="{{ $kom->id_komoditas }}" {{ (old('id_komoditas') ?? $defaultKomoditas) == $kom->id_komoditas ? 'selected' : '' }}>
                                    {{ $kom->nama_komoditas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label fw-bold text-dark">TANGGAL PELAKSANAAN PANEN</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- BARIS 3: PERALATAN & PERLENGKAPAN -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">PERALATAN (ALAT KERJA)</label>
                        <div class="p-3 border rounded bg-white" style="max-height: 200px; overflow-y: auto;">
                            @foreach($peralatan as $alat)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="id_peralatan[]" value="{{ $alat->id_peralatan }}" id="alat_{{ $alat->id_peralatan }}" {{ in_array($alat->id_peralatan, old('id_peralatan', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="alat_{{ $alat->id_peralatan }}">
                                        {{ $alat->nama_peralatan }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('id_peralatan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">PERLENGKAPAN</label>
                        <div class="p-3 border rounded bg-white" style="max-height: 200px; overflow-y: auto;">
                            @foreach($perlengkapan as $lengkap)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="id_perlengkapan[]" value="{{ $lengkap->id_perlengkapan }}" id="lengkap_{{ $lengkap->id_perlengkapan }}" {{ in_array($lengkap->id_perlengkapan, old('id_perlengkapan', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="lengkap_{{ $lengkap->id_perlengkapan }}">
                                        {{ $lengkap->nama_perlengkapan }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('id_perlengkapan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-success px-4 btn-next" data-next="step-2">Selanjutnya (Upah Pekerja) <i class="fa-solid fa-arrow-right ms-1"></i></button>
                </div>
            </div>

            <!-- STEP 2: UPAH PEKERJA -->
            <div id="step-2" class="form-step d-none">
                <h4 class="mb-4 text-success fw-bold border-bottom pb-2">Langkah 2: Upah Pekerja (Opsional)</h4>
                <div class="alert alert-info">Isi bagian ini jika Anda juga ingin mencatat pembayaran upah pekerja panen. Jika tidak ada, Anda bisa langsung lanjut.</div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">KOMODITAS</label>
                        <input type="text" class="form-control bg-light" id="display_komoditas" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">TANGGAL KEGIATAN</label>
                        <input type="text" class="form-control bg-light" id="display_tanggal" readonly>
                    </div>
                </div>

                <div id="pekerja-container">
                    <!-- PEKERJA ROW 1 -->
                    <div class="pekerja-row border rounded p-3 mb-3 bg-white position-relative">
                        <h6 class="text-success fw-bold border-bottom pb-2 mb-3">Data Pekerja</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">NAMA PEKERJA</label>
                                <input type="text" name="nama_pekerja[]" class="form-control" placeholder="Contoh: Budi, Santoso, dll.">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">JENIS PEKERJAAN</label>
                                <input type="text" name="jenis_pekerjaan[]" class="form-control bg-light" value="Panen" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">JUMLAH HARI (HKO)</label>
                                <input type="number" name="jumlah_hari[]" class="form-control" value="1" min="1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">NOMINAL DIBAYAR PER HARI (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="tarif_harian[]" class="form-control" placeholder="Contoh: 100000">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="button" class="btn btn-outline-success btn-sm fw-bold" id="btn-tambah-pekerja">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Data Pekerja
                    </button>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-light border px-4 btn-prev" data-prev="step-1"><i class="fa-solid fa-arrow-left me-1"></i> Sebelumnya</button>
                    <button type="button" class="btn btn-success px-4 btn-next" data-next="step-3">Selanjutnya (Hasil Panen) <i class="fa-solid fa-arrow-right ms-1"></i></button>
                </div>
            </div>

            <!-- STEP 3: HASIL PANEN -->
            <div id="step-3" class="form-step d-none">
                <h4 class="mb-4 text-success fw-bold border-bottom pb-2">Langkah 3: Timbangan Hasil Panen</h4>
                
                <div class="row mb-4 p-4 rounded border bg-light">
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold text-dark m-0">TIMBANGAN HASIL PANEN (Kilogram)</label>
                        <p class="text-muted small m-0 mb-3">Netto adalah berat bersih setelah dikurangi wadah (Gross).</p>
                    </div>
                    
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="gross_panen" class="form-label fw-bold text-secondary">HASIL PANEN KOTOR (GROSS)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="gross_panen" id="gross_panen" class="form-control form-control-lg @error('gross_panen') is-invalid @enderror" value="{{ old('gross_panen') }}" placeholder="0.00" required>
                            <span class="input-group-text bg-white fw-bold">Kg</span>
                        </div>
                        @error('gross_panen')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="netto_panen" class="form-label fw-bold text-success"><i class="fa-solid fa-scale-balanced me-1"></i> HASIL PANEN BERSIH (NETTO)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="netto_panen" id="netto_panen" class="form-control form-control-lg border-success text-success fw-bold @error('netto_panen') is-invalid @enderror" value="{{ old('netto_panen') }}" placeholder="0.00" required>
                            <span class="input-group-text bg-success text-white fw-bold">Kg</span>
                        </div>
                        @error('netto_panen')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="keterangan" class="form-label fw-bold text-dark">KETERANGAN (OPSIONAL)</label>
                        <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan (contoh: 'Kondisi cuaca hujan saat panen' atau 'Panen blok timur')">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="text-muted">

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-light border px-4 btn-prev" data-prev="step-2"><i class="fa-solid fa-arrow-left me-1"></i> Sebelumnya</button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fa-solid fa-save me-1"></i> Simpan Hasil Panen
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navigasi Wizard
    const nextButtons = document.querySelectorAll('.btn-next');
    const prevButtons = document.querySelectorAll('.btn-prev');
    const steps = document.querySelectorAll('.form-step');
    const indicators = document.querySelectorAll('.step-item');

    function showStep(stepId) {
        // Hide all steps
        steps.forEach(step => step.classList.add('d-none'));
        // Show target step
        document.getElementById(stepId).classList.remove('d-none');
        
        // Update indicators
        const stepNum = stepId.split('-')[1];
        indicators.forEach((indicator, index) => {
            if (index < stepNum) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
    }

    nextButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update readonly fields for Step 2
            if (this.getAttribute('data-next') === 'step-2') {
                const komoditasSelect = document.getElementById('id_komoditas');
                const selectedKomoditasText = komoditasSelect.options[komoditasSelect.selectedIndex].text;
                document.getElementById('display_komoditas').value = selectedKomoditasText;
                
                document.getElementById('display_tanggal').value = document.getElementById('tanggal').value;
            }
            
            showStep(this.getAttribute('data-next'));
        });
    });

    prevButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            showStep(this.getAttribute('data-prev'));
        });
    });

    // Tambah Baris Pekerja
    const btnTambah = document.getElementById('btn-tambah-pekerja');
    const container = document.getElementById('pekerja-container');

    btnTambah.addEventListener('click', function() {
        const firstRow = container.querySelector('.pekerja-row');
        const newRow = firstRow.cloneNode(true);
        
        // Bersihkan nilai input di baris baru
        newRow.querySelectorAll('input:not([readonly])').forEach(input => {
            if(input.type === 'number' && input.name === 'jumlah_hari[]') {
                input.value = 1;
            } else {
                input.value = '';
            }
        });

        // Tambahkan tombol hapus jika belum ada
        if (!newRow.querySelector('.btn-hapus-pekerja')) {
            const hapusBtn = document.createElement('button');
            hapusBtn.type = 'button';
            hapusBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-hapus-pekerja';
            hapusBtn.innerHTML = '<i class="fa-solid fa-times"></i> Hapus';
            hapusBtn.onclick = function() {
                newRow.remove();
            };
            newRow.appendChild(hapusBtn);
        }

        container.appendChild(newRow);
    });
});
</script>
@endsection
