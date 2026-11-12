@extends('layouts.app')

@section('title', 'Input Panen Lada')

@section('styles')
<style>
    /* Theme overrides for Lada theme */
    :root {
        --primary-green: #065f46;
        --emerald-green: #10b981;
        --light-green: #f0fdf4;
        --mint-green: #d1fae5;
        --accent-green: #059669;
    }

    .sidebar-link.active {
        color: #065f46 !important;
        background-color: #d1fae5 !important;
        border-left: 4px solid #10b981 !important;
    }

    .btn-add-land {
        background-color: #065f46 !important;
    }
    .btn-add-land:hover {
        background-color: #047857 !important;
    }

    .text-success {
        color: #065f46 !important;
    }

    .form-card-panen {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.01);
    }

    .form-title-custom {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--primary-green);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    .grade-btn-group {
        display: flex;
        gap: 12px;
    }

    .grade-btn {
        flex: 1;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background-color: #ffffff;
        color: #475569;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        padding: 10px;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .grade-btn:hover {
        background-color: #f8fafc;
    }

    .grade-btn.active {
        border-color: var(--primary-green);
        background-color: var(--light-green);
        color: var(--primary-green);
    }

    .card-footer-stats {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .card-footer-stats .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: var(--light-green);
        color: var(--primary-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .card-footer-stats .label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin: 0;
    }

    .card-footer-stats .value {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        font-family: 'Outfit', sans-serif;
        margin-top: 2px;
    }

    .table-panen th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 14px 16px;
        background-color: #f8fafc;
    }

    .table-panen td {
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.88rem;
    }

    .badge-grade {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        text-transform: uppercase;
    }
    .badge-grade-a { background-color: #d1fae5; color: #065f46; }
    .badge-grade-b { background-color: #f1f5f9; color: #475569; }
    .badge-grade-c { background-color: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Input Panen Lada</h2>
        <p class="text-muted m-0">Pencatatan data hasil panen lada putih dan hitam harian secara real-time.</p>
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-secondary" onclick="alert('Mengekspor laporan panen lada...');" style="padding: 8px 16px;">
            <i class="fa-solid fa-download me-1"></i> Export Data
        </button>
    </div>
</div>

<!-- Layout: Input Form & Logs -->
<div class="row mb-4">
    <!-- Form Panel Left -->
    <div class="col-lg-5 mb-4 mb-lg-0">
        <div class="form-card-panen">
            <div class="form-title-custom">
                <i class="fa-solid fa-scale-balanced"></i> Panen/Log Panen
            </div>
            
            <form method="POST" action="{{ route('panen.store') }}" id="panen-lada-form">
                @csrf
                
                <!-- Date -->
                <div class="mb-3">
                    <label for="tanggal_panen" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Tanggal Panen</label>
                    <input type="date" name="tanggal_panen" id="tanggal_panen" class="form-control" required>
                </div>
                
                <div class="row mb-3">
                    <!-- Blok -->
                    <div class="col-md-6">
                        <label for="area_kebun_id" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Pilih Blok Lada</label>
                        <select name="area_kebun_id" id="area_kebun_id" class="form-select" required>
                            @foreach($dataLahan as $lahan)
                                <option value="{{ $lahan->id }}">{{ $lahan->nama_lahan }}</option>
                            @endforeach
                            @if(count($dataLahan) == 0)
                                <option value="">Blok Utara A1</option>
                                <option value="">Blok Selatan A2</option>
                                <option value="">Blok Timur B1</option>
                            @endif
                        </select>
                    </div>
                    <!-- Jenis Lada -->
                    <div class="col-md-6">
                        <label for="jenis_lada" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Jenis Lada</label>
                        <select id="jenis_lada" class="form-select" required>
                            <option value="Lada Putih">Lada Putih</option>
                            <option value="Lada Hitam">Lada Hitam</option>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <!-- Gross -->
                    <div class="col-md-6">
                        <label for="gross_panen" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Berat Kotor (Gross KG)</label>
                        <input type="number" step="0.01" name="gross_panen" id="gross_panen" class="form-control" placeholder="0.00" required>
                    </div>
                    <!-- Netto -->
                    <div class="col-md-6">
                        <label for="netto_panen" class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Berat Bersih (Netto KG)</label>
                        <input type="number" step="0.01" name="netto_panen" id="netto_panen" class="form-control" placeholder="0.00" required>
                    </div>
                </div>
                
                <!-- Grade selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.85rem;">Kualitas (Grade)</label>
                    <div class="grade-btn-group">
                        <button type="button" class="grade-btn active" data-grade="Grade A">A</button>
                        <button type="button" class="grade-btn" data-grade="Grade B">B</button>
                        <button type="button" class="grade-btn" data-grade="Grade C">C</button>
                    </div>
                </div>
                
                <!-- Hidden fields mapping to DB -->
                <input type="hidden" name="harga_jual" value="55000"> <!-- Default lada price per KG -->
                <input type="hidden" name="keterangan" id="keterangan" value="Lada Putih, Grade A">
                
                <!-- Submit -->
                <button type="submit" class="btn btn-success w-100" style="background-color: var(--primary-green); border-color: var(--primary-green); padding: 12px; border-radius: 8px; font-weight: 600;">
                    Simpan Data Panen
                </button>
            </form>
        </div>
    </div>
    
    <!-- Table Panel Right -->
    <div class="col-lg-7">
        <div class="card h-100 mb-0">
            <div class="card-header bg-white py-3">
                <span class="section-title"><i class="fa-solid fa-clock-rotate-left me-2"></i>Log Panen Terakhir</span>
            </div>
            
            <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div class="table-responsive">
                    <table class="table table-panen align-middle m-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Blok</th>
                                <th>Jenis</th>
                                <th>Berat Bersih</th>
                                <th class="text-end pe-4">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPanen as $panen)
                                <tr>
                                    <td class="ps-4 text-muted">{{ date('d M Y', strtotime($panen->tanggal_panen)) }}</td>
                                    <td class="fw-semibold text-dark">{{ $panen->areaKebun->nama_lahan ?? 'Blok Lada' }}</td>
                                    <td>{{ $panen->varietas }}</td>
                                    <td class="fw-bold text-dark">{{ number_format($panen->netto_panen, 1, ',', '.') }} kg</td>
                                    <td class="text-end pe-4">
                                        <span class="badge-status {{ str_contains($panen->grade, 'Grade A') ? 'badge-produktif' : (str_contains($panen->grade, 'Grade C') ? 'badge-replanting' : 'badge-resting') }} text-uppercase" style="font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                            {{ str_replace('Grade ', '', $panen->grade) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <!-- Mock rows fallback if database is empty -->
                                <tr>
                                    <td class="ps-4 text-muted">24 Okt 2023</td>
                                    <td class="fw-semibold text-dark">Utara A1</td>
                                    <td>Lada Putih</td>
                                    <td class="fw-bold text-dark">45,5 kg</td>
                                    <td class="text-end pe-4"><span class="badge-status badge-produktif">Grade A</span></td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-muted">23 Okt 2023</td>
                                    <td class="fw-semibold text-dark">Utara A2</td>
                                    <td>Lada Hitam</td>
                                    <td class="fw-bold text-dark">32,0 kg</td>
                                    <td class="text-end pe-4"><span class="badge-status badge-resting">Grade B</span></td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-muted">22 Okt 2023</td>
                                    <td class="fw-semibold text-dark">Selatan B1</td>
                                    <td>Lada Putih</td>
                                    <td class="fw-bold text-dark">28,8 kg</td>
                                    <td class="text-end pe-4"><span class="badge-status badge-produktif">Grade A</span></td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-muted">21 Okt 2023</td>
                                    <td class="fw-semibold text-dark">Barat C1</td>
                                    <td>Lada Hitam</td>
                                    <td class="fw-bold text-dark">15,2 kg</td>
                                    <td class="text-end pe-4"><span class="badge-status badge-replanting">Grade C</span></td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-muted">20 Okt 2023</td>
                                    <td class="fw-semibold text-dark">Utara A1</td>
                                    <td>Lada Putih</td>
                                    <td class="fw-bold text-dark">40,1 kg</td>
                                    <td class="text-end pe-4"><span class="badge-status badge-produktif">Grade A</span></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($dataPanen instanceof \Illuminate\Pagination\LengthAwarePaginator && $dataPanen->total() > 10)
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light">
                        <span class="text-muted" style="font-size: 0.8rem;">
                            Menampilkan {{ $dataPanen->firstItem() }}-{{ $dataPanen->lastItem() }} dari {{ $dataPanen->total() }} log
                        </span>
                        <div>
                            {{ $dataPanen->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bottom performance statistics cards -->
<div class="row">
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="card-footer-stats">
            <div class="icon-box">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <p class="label">Total Panen Minggu ini</p>
                <div class="value">{{ number_format($totalPanenMingguIni, 1, ',', '.') }} KG</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card-footer-stats">
            <div class="icon-box">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="label">Kualitas Terbaik (Grade A)</p>
                <div class="value">{{ $kualitasTerbaik }}% <span class="text-success fw-normal" style="font-size: 0.85rem; font-family: 'Inter'; font-weight: 500;">+5% pts</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Set date default value to today
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_panen').val(today);
        
        // Handle grade buttons
        let selectedGrade = 'Grade A';
        $('.grade-btn').click(function(e) {
            e.preventDefault();
            $('.grade-btn').removeClass('active');
            $(this).addClass('active');
            selectedGrade = $(this).data('grade');
            updateKeteranganField();
        });
        
        // Handle dropdown change
        $('#jenis_lada').change(function() {
            updateKeteranganField();
        });
        
        function updateKeteranganField() {
            const jenisLada = $('#jenis_lada').val();
            $('#keterangan').val(jenisLada + ', ' + selectedGrade);
        }
    });
</script>
@endsection
