@extends('layouts.app')

@section('title', 'Input Panen Kopi')

@section('styles')
<style>
    /* Theme overrides for Coffee theme */
    :root {
        --primary-green: #3d2314; /* Deep coffee espresso */
        --emerald-green: #5c3a21; /* Warm coffee brown */
        --light-green: #faf7f2; /* Creamy beige */
        --mint-green: #e6ccb2; /* Light coffee bean */
        --accent-green: #c9a054; /* Gold accent */
    }

    /* Overrides */
    .sidebar-link.active {
        color: #3d2314 !important;
        background-color: #e6ccb2 !important;
        border-left: 4px solid #c9a054 !important;
    }
    .btn-add-land {
        background-color: #3d2314 !important;
    }
    .btn-add-land:hover {
        background-color: #5c3a21 !important;
    }
    .text-success {
        color: #5c3a21 !important;
    }

    /* Step display */
    .progress-step-container-kopi {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        background-color: #ffffff;
        padding: 16px 24px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .step-circle-kopi {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #3d2314;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .step-circle-kopi.inactive {
        background-color: #e2e8f0;
        color: #8a7a71;
    }

    .step-label-kopi {
        font-size: 0.88rem;
        font-weight: 600;
        color: #3d2314;
    }

    .step-label-kopi.inactive {
        color: #8a7a71;
    }

    .step-line-kopi {
        flex-grow: 1;
        height: 2px;
        background-color: #e2e8f0;
        margin: 0 10px;
    }

    /* Form Design */
    .form-card-kopi-panen {
        border-radius: 16px;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 24px;
    }

    .timbangan-box-kopi {
        background-color: #faf7f2;
        border: 1px solid #e6ccb2;
        border-radius: 12px;
        padding: 20px;
        margin-top: 24px;
    }

    .timbangan-box-title-kopi {
        font-size: 0.9rem;
        font-weight: 700;
        color: #5c3a21;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    /* Interactive toggle buttons for variety & grade */
    .toggle-group-kopi {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .btn-toggle-kopi {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #7f6e62;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-align: center;
        cursor: pointer;
    }

    .btn-toggle-kopi:hover {
        background-color: #faf7f2;
        color: #3d2314;
        border-color: #e6ccb2;
    }

    .btn-toggle-kopi.active {
        background-color: #5c3a21;
        color: #ffffff;
        border-color: #5c3a21;
        box-shadow: 0 4px 10px rgba(92, 58, 33, 0.15);
    }

    /* Side statistics cards */
    .side-card-kopi-yield {
        background: linear-gradient(135deg, #5c3a21 0%, #3d2314 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .side-card-kopi-yield::after {
        content: '\f0fc';
        font-family: 'FontAwesome';
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-size: 6rem;
        color: rgba(255, 255, 255, 0.04);
        pointer-events: none;
    }

    .side-card-kopi-yield .yield-lbl {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #e6ccb2;
        letter-spacing: 0.8px;
    }

    .side-card-kopi-yield .yield-val {
        font-size: 1.8rem;
        font-weight: 800;
        margin-top: 8px;
        font-family: 'Outfit';
    }

    .side-card-kopi-processing {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
    }

    .proc-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #3d2314;
    }

    .proc-name {
        font-weight: 700;
        color: #5c3a21;
    }

    .proc-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        background-color: #fef3c7;
        color: #92400e;
        text-transform: uppercase;
    }

    /* Table styles */
    .table-coffee-panen th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #8a7a71;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px;
        background-color: #faf7f2;
    }

    .table-coffee-panen td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }

    .badge-coffee-variety {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        border: 1px solid;
    }

    .badge-coffee-variety.robusta {
        background-color: #f5efe6;
        color: #5c3a21;
        border-color: #e6ccb2;
    }

    .badge-coffee-variety.arabica {
        background-color: #eef2f3;
        color: #0f4c81;
        border-color: #cbd5e1;
    }

    .badge-coffee-grade {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 30px;
        background-color: #e6ccb2;
        color: #3d2314;
    }

    .badge-coffee-grade.grade-a {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-coffee-grade.grade-b {
        background-color: #fef3c7;
        color: #92400e;
    }

    .badge-coffee-grade.grade-c {
        background-color: #f1f5f9;
        color: #475569;
    }

    /* Live estimation info */
    .live-calc-box {
        margin-top: 15px;
        padding: 12px;
        background-color: #faf7f2;
        border-radius: 8px;
        border: 1px dashed #e6ccb2;
        font-size: 0.85rem;
        font-weight: 600;
        color: #5c3a21;
        display: none;
    }
</style>
@endsection

@section('content')
<!-- Header Row -->
<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="m-0 text-success fw-bold">Pencatatan Panen Kopi</h2>
        <p class="text-muted m-0">Input penimbangan buah kopi ceri basah (basah/gelondong) dan tentukan grade kualitas hasil panen.</p>
    </div>
    <div class="col-auto d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="alert('Laporan berhasil diexport!');" style="padding: 8px 16px; border-color: #e6ccb2; color: #3d2314;">
            <i class="fa-solid fa-download me-1"></i> Export Data Kopi
        </button>
    </div>
</div>

<!-- Grid Input & Statistics -->
<div class="row mb-5">
    <!-- Form Card Left -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <!-- Progress Steps -->
        <div class="progress-step-container-kopi">
            <div class="step-circle-kopi">1</div>
            <div class="step-label-kopi">Varietas & Blok Asal</div>
            <div class="step-line-kopi"></div>
            <div class="step-circle-kopi inactive">2</div>
            <div class="step-label-kopi inactive">Timbangan & Grade</div>
        </div>

        <!-- Form -->
        <div class="form-card-kopi-panen">
            <form method="POST" action="{{ route('panen.store') }}" id="panenCoffeeForm">
                @csrf
                <!-- Dynamic hidden values for database compatibility -->
                <input type="hidden" id="jenis_tanaman" name="jenis_tanaman" value="kopi">
                <input type="hidden" id="keterangan" name="keterangan" value="Premium">
                <input type="hidden" id="harga_jual" name="harga_jual" value="35000">
                <input type="hidden" id="jumlah_panen_gross" name="jumlah_panen_gross" value="">
                <input type="hidden" id="jumlah_netto" name="jumlah_netto" value="">
                <input type="hidden" id="jumlah_panen" name="jumlah_panen" value="">
                <input type="hidden" id="panen_brondol" name="panen_brondol" value="0">

                <!-- 1. Varietas Selection Toggle -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Varietas Kopi</label>
                    <div class="toggle-group-kopi">
                        <button type="button" class="btn-toggle-kopi active" id="btnRobusta" onclick="selectVariety('Robusta')">
                            <i class="fa-solid fa-mug-hot me-2"></i> Robusta Gayo
                        </button>
                        <button type="button" class="btn-toggle-kopi" id="btnArabica" onclick="selectVariety('Arabika')">
                            <i class="fa-solid fa-mug-saucer me-2"></i> Arabica Typica
                        </button>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="tanggal_panen" class="form-label fw-bold text-dark">Tanggal Panen</label>
                        <input type="date" name="tanggal_panen" id="tanggal_panen" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="area_kebun_id" class="form-label fw-bold text-dark">Blok Asal Kopi</label>
                        <select name="area_kebun_id" id="area_kebun_id" class="form-select" required>
                            @foreach($dataLahan as $lahan)
                                <option value="{{ $lahan->id }}" data-varietas="{{ str_contains($lahan->nama_lahan, 'Arabika') ? 'Arabika' : 'Robusta' }}">
                                    {{ $lahan->nama_lahan }} (Populasi: {{ $lahan->jumlah_tanaman }} Pohon)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 2. Kualitas Grade Selection Toggle -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Kualitas Hasil (Grade)</label>
                    <div class="toggle-group-kopi">
                        <button type="button" class="btn-toggle-kopi active" id="btnGradeA" onclick="selectGrade('A', 'Premium')">
                            Grade A (Premium Ceri Merah >90%)
                        </button>
                        <button type="button" class="btn-toggle-kopi" id="btnGradeB" onclick="selectGrade('B', 'Validated')">
                            Grade B (Campur Ceri Kuning/Merah)
                        </button>
                        <button type="button" class="btn-toggle-kopi" id="btnGradeC" onclick="selectGrade('C', 'Standard')">
                            Grade C (Ceri Hijau / Asalan)
                        </button>
                    </div>
                </div>

                <!-- 3. Timbangan Detail Box -->
                <div class="timbangan-box-kopi">
                    <div class="timbangan-box-title-kopi">
                        <i class="fa-solid fa-scale-unbalanced-flip"></i> Timbangan Ceri Basah Kopi
                    </div>
                    
                    <div class="row">
                        <!-- Gross -->
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="gross_panen" class="form-label fw-semibold text-muted">GROSS WEIGHT (KG)</label>
                            <input type="number" step="0.01" name="gross_panen" id="gross_panen" class="form-control form-control-lg bg-white" placeholder="0.00" oninput="calculateNetto()" required>
                        </div>
                        
                        <!-- Netto -->
                        <div class="col-md-6">
                            <label for="netto_panen" class="form-label fw-semibold text-muted">NETTO WEIGHT (KG)</label>
                            <input type="number" step="0.01" name="netto_panen" id="netto_panen" class="form-control form-control-lg bg-white" placeholder="0.00" oninput="validateNetto()" required>
                        </div>
                    </div>

                    <!-- Live Revenue Calculator -->
                    <div id="liveCalcBox" class="live-calc-box">
                        Estimasi Nilai Panen: <span id="valTotalRevenue" style="color: #3d2314; font-weight: 800;">Rp 0</span> 
                        <span class="text-muted fw-normal">(<span id="valNettoKg">0</span> kg @ Rp <span id="valHargaPerKg">0</span>/kg)</span>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2" style="background-color: #3d2314; border-color: #3d2314; border-radius: 8px; font-weight: 600;">
                        Catat Log Panen Kopi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Side Stats Cards Right -->
    <div class="col-lg-4">
        <!-- Total Produksi Hari Ini -->
        <div class="side-card-kopi-yield">
            <span class="yield-lbl">PANEN HARI INI</span>
            <div class="yield-val">{{ number_format($totalPanenHariIni, 0, ',', '.') }} KG</div>
            <div class="mt-2 text-white-50" style="font-size: 0.8rem;">
                <i class="fa-solid fa-cloud-sun-rain"></i> Kondisi Sektor: Cerah Berawan
            </div>
        </div>

        <!-- Dry Mill Processing Capacity -->
        <div class="side-card-kopi-processing">
            <span class="text-muted font-monospace" style="font-size: 0.72rem; letter-spacing: 0.5px;">KAPASITAS DRY MILL</span>
            <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                <span class="proc-name">Kubah Pengering A-2</span>
                <span class="proc-badge">85% Terisi</span>
            </div>
            
            <div class="progress" style="height: 8px; background-color: #faf7f2;">
                <div class="progress-bar" style="width: 85%; background-color: #5c3a21;"></div>
            </div>
            <div class="mt-2 text-muted" style="font-size: 0.8rem;">
                Tersedia: <strong>1.5 / 10.0 Tons Dry Mill</strong>
            </div>
        </div>
    </div>
</div>

<!-- Table Log Panen Kopi Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="section-title">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Log Panen Kopi Terbaru
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-coffee-panen align-middle m-0" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th>BATCH CODE</th>
                                <th>TANGGAL</th>
                                <th>BLOK ASAL</th>
                                <th>VARIETAS</th>
                                <th>GROSS (KG)</th>
                                <th>NETTO (KG)</th>
                                <th>KUALITAS GRADE</th>
                                <th>ESTIMASI REVENUE</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPanen as $panen)
                                <tr>
                                    <td class="fw-bold text-dark">
                                        #COF-{{ date('Y', strtotime($panen->tanggal_panen)) }}-{{ str_pad($panen->id, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td>{{ date('d M Y', strtotime($panen->tanggal_panen)) }}</td>
                                    <td class="fw-bold">{{ $panen->areaKebun->nama_lahan ?? 'Blok Kebun' }}</td>
                                    <td>
                                        <span class="badge-coffee-variety {{ strtolower($panen->varietas) }}">
                                            {{ $panen->varietas }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($panen->gross_panen, 0, ',', '.') }} kg</td>
                                    <td class="fw-bold text-dark">{{ number_format($panen->netto_panen, 0, ',', '.') }} kg</td>
                                    <td>
                                        @php
                                            $gradeLabel = str_contains($panen->keterangan ?? '', 'Premium') ? 'Grade A' : (str_contains($panen->keterangan ?? '', 'Validated') ? 'Grade B' : 'Grade C');
                                            $gradeClass = str_contains($panen->keterangan ?? '', 'Premium') ? 'grade-a' : (str_contains($panen->keterangan ?? '', 'Validated') ? 'grade-b' : 'grade-c');
                                        @endphp
                                        <span class="badge-coffee-grade {{ $gradeClass }}">
                                            {{ $gradeLabel }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-success">
                                        Rp {{ number_format($panen->total_pendapatan, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="#" onclick="alert('Membagikan data log timbangan kopi...');" class="text-muted"><i class="fa-solid fa-share-nodes"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                                        <p class="m-0">Belum ada data pencatatan panen kopi yang masuk.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination Info -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted" style="font-size: 0.88rem;">
        Menampilkan {{ $dataPanen->firstItem() ?? 0 }}-{{ $dataPanen->lastItem() ?? 0 }} dari total {{ $dataPanen->total() }} data panen
    </div>
    <div>
        {{ $dataPanen->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Selected Variety & Grade logic
    let selectedVariety = 'Robusta';
    let selectedGrade = 'A';
    let gradeLabelText = 'Premium';

    // Pricing grid
    const priceList = {
        'Arabika': { 'A': 45000, 'B': 38000, 'C': 20000 },
        'Robusta': { 'A': 35000, 'B': 28000, 'C': 20000 }
    };

    $(document).ready(function() {
        // Set default date picker value to today
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_panen').val(today);

        // Set initial keterangan
        updateKeteranganField();

        // Filter block dropdown based on default selected variety
        filterBlockDropdown();

        // Form submit integration helper
        $('#panenCoffeeForm').on('submit', function() {
            const gross = parseFloat($('#gross_panen').val());
            const netto = parseFloat($('#netto_panen').val());
            
            // Set duplicate inputs for legacy columns compatibility
            $('#jumlah_panen').val(gross);
            $('#jumlah_panen_gross').val(gross);
            $('#jumlah_netto').val(netto);
        });
    });

    function selectVariety(variety) {
        selectedVariety = variety;
        
        // Update toggles
        if (variety === 'Robusta') {
            $('#btnRobusta').addClass('active');
            $('#btnArabica').removeClass('active');
        } else {
            $('#btnArabica').addClass('active');
            $('#btnRobusta').removeClass('active');
        }
        
        // Filter block dropdown options
        filterBlockDropdown();
        
        // Update hidden keterangan field
        updateKeteranganField();
        
        // Recalculate price and live value
        updatePriceAndRevenue();
    }

    function filterBlockDropdown() {
        const valMap = { 'Robusta': 'Robusta', 'Arabika': 'Arabika' };
        const key = selectedVariety; // 'Robusta' or 'Arabika'

        let firstSelect = false;
        $('#area_kebun_id option').each(function() {
            const optVariety = $(this).data('varietas'); // 'Robusta' or 'Arabika'
            if (optVariety === key) {
                $(this).show();
                if (!firstSelect) {
                    $(this).prop('selected', true);
                    firstSelect = true;
                }
            } else {
                $(this).hide();
            }
        });
    }

    function selectGrade(grade, description) {
        selectedGrade = grade;
        gradeLabelText = description;

        // Update toggles
        $('#btnGradeA, #btnGradeB, #btnGradeC').removeClass('active');
        if (grade === 'A') $('#btnGradeA').addClass('active');
        else if (grade === 'B') $('#btnGradeB').addClass('active');
        else if (grade === 'C') $('#btnGradeC').addClass('active');

        // Update hidden keterangan field
        updateKeteranganField();

        // Recalculate price and live value
        updatePriceAndRevenue();
    }

    function updateKeteranganField() {
        $('#keterangan').val(selectedVariety + ', ' + gradeLabelText);
    }

    function updatePriceAndRevenue() {
        // Find price per kg
        const price = priceList[selectedVariety][selectedGrade] || 20000;
        $('#harga_jual').val(price);

        // Get current netto
        const netto = parseFloat($('#netto_panen').val()) || 0;
        const totalRevenue = netto * price;

        if (netto > 0) {
            $('#valTotalRevenue').text(formatRupiah(totalRevenue));
            $('#valNettoKg').text(netto);
            $('#valHargaPerKg').text(price.toLocaleString('id-ID'));
            $('#liveCalcBox').fadeIn();
        } else {
            $('#liveCalcBox').fadeOut();
        }
    }

    function calculateNetto() {
        const gross = parseFloat($('#gross_panen').val()) || 0;
        
        // Default: netto is 95% of gross if not manually typed yet
        const expectedNetto = (gross * 0.95).toFixed(2);
        $('#netto_panen').val(expectedNetto);

        updatePriceAndRevenue();
    }

    function validateNetto() {
        const gross = parseFloat($('#gross_panen').val()) || 0;
        const netto = parseFloat($('#netto_panen').val()) || 0;
        
        if (netto > gross) {
            alert('Netto weight cannot be larger than Gross weight!');
            $('#netto_panen').val(gross);
        }

        updatePriceAndRevenue();
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }
</script>
@endsection
