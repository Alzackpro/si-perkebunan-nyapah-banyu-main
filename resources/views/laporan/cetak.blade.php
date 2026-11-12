<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan {{ ucfirst($jenis) }} - Nyapah Banyu</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            color: #333333;
            background-color: #ffffff;
        }

        .header-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: #135d37;
            border-bottom: 2px solid #135d37;
            padding-bottom: 5px;
            font-size: 1.6rem;
        }

        .laporan-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 1.15rem;
            letter-spacing: 0.5px;
        }

        .table th {
            background-color: #f1f3f5 !important;
            color: #212529 !important;
            font-weight: 600;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid my-4">
    <!-- Bar Kontrol No Print -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print bg-light p-3 rounded border">
        <span><i class="fa-solid fa-print me-1 text-success"></i> Halaman Dokumen Cetak Cetak</span>
        <div>
            <button onclick="window.print()" class="btn btn-success btn-sm me-2">Cetak Laporan</button>
            <button onclick="window.close()" class="btn btn-outline-secondary btn-sm">Tutup Halaman</button>
        </div>
    </div>

    <!-- Kop Surat Laporan -->
    <div class="row align-items-center mb-4">
        <div class="col-8">
            <div class="header-title text-uppercase">Perkebunan Nyapah Banyu</div>
            <p class="m-0 text-muted small mt-1">
                Kec. Abung Barat, Kabupaten Lampung Utara, Lampung, Indonesia<br>
                Telepon: +62 822-8123-XXXX | Email: info@nyapahbanyu.com
            </p>
        </div>
        <div class="col-4 text-end">
            <span class="text-muted d-block small">TANGGAL CETAK</span>
            <span class="fw-bold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }}</span>
        </div>
    </div>

    <div class="text-center my-4">
        <h4 class="laporan-title m-0">LAPORAN DATA {{ strtoupper($jenis) }}</h4>
        <span class="text-muted">Periode: {{ date('d-m-Y', strtotime($mulai)) }} s.d. {{ date('d-m-Y', strtotime($akhir)) }}</span>
    </div>

    <!-- DATA TABEL SESUAI JENIS -->
    @if($jenis == 'panen')
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal Panen</th>
                    <th>Lahan Kebun</th>
                    <th>Komoditas</th>
                    <th class="text-end">Berat Gross</th>
                    <th class="text-end">Berat Netto</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-end">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGross = 0; $totalNetto = 0; $totalPend = 0; @endphp
                @foreach($dataLaporan as $index => $panen)
                    @php 
                        $totalGross += $panen->jumlah_panen_gross;
                        $totalNetto += $panen->jumlah_netto;
                        $totalPend += $panen->total_pendapatan;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($panen->tanggal_panen)) }}</td>
                        <td>{{ $panen->areaKebun->nama_lahan }}</td>
                        <td class="text-uppercase">{{ $panen->jenis_tanaman }}</td>
                        <td class="text-end">{{ number_format($panen->jumlah_panen_gross, 2, ',', '.') }} kg</td>
                        <td class="text-end">{{ number_format($panen->jumlah_netto, 2, ',', '.') }} kg</td>
                        <td class="text-end">Rp {{ number_format($panen->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($panen->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="fw-bold">
                <tr>
                    <td colspan="4" class="text-end">TOTAL :</td>
                    <td class="text-end">{{ number_format($totalGross, 2, ',', '.') }} kg</td>
                    <td class="text-end">{{ number_format($totalNetto, 2, ',', '.') }} kg</td>
                    <td></td>
                    <td class="text-end">Rp {{ number_format($totalPend, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    @elseif($jenis == 'operasional')
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Lahan Kebun</th>
                    <th>Jenis Perawatan</th>
                    <th>Keterangan</th>
                    <th>Pegawai Pelaksana</th>
                    <th>Peralatan & Perlengkapan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataLaporan as $index => $ops)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($ops->tanggal_perawatan)) }}</td>
                        <td>{{ optional($ops->areaKebun)->nama_lahan ?? '-' }}</td>
                        <td>{{ $ops->jenis_perawatan }}</td>
                        <td>{{ $ops->keterangan ?: '-' }}</td>
                        <td>{{ optional($ops->pegawai)->nama_pegawai ?? '-' }}</td>
                        <td>
                            {{ optional($ops->peralatan)->nama_peralatan ?? '-' }}<br>
                            {{ optional($ops->perlengkapan)->nama_perlengkapan ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($jenis == 'pengiriman')
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal Pengiriman</th>
                    <th>Lahan Asal</th>
                    <th>Komoditas</th>
                    <th>Keterangan</th>
                    <th class="text-end">Jumlah Dikirim</th>
                    <th class="text-end">Total Penjualan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $totalKirim = 0; $totalPendapatanKirim = 0; @endphp
                @foreach($dataLaporan as $index => $kirim)
                    @php 
                        $totalKirim += $kirim->jumlah_dikirim;
                        $totalPendapatanKirim += $kirim->total_penjualan;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($kirim->tanggal_pengiriman)) }}</td>
                        <td>{{ optional(optional($kirim->panen)->areaKebun)->nama_lahan ?? '-' }}</td>
                        <td class="text-uppercase">{{ optional($kirim->komoditas)->nama_komoditas ?? '-' }}</td>
                        <td>{{ $kirim->keterangan ?: '-' }}</td>
                        <td class="text-end fw-bold">{{ number_format($kirim->jumlah_dikirim, 2, ',', '.') }} kg</td>
                        <td class="text-end text-primary">Rp {{ number_format($kirim->total_penjualan, 0, ',', '.') }}</td>
                        <td class="text-uppercase">{{ optional($kirim->status_pengiriman)->nama_status ?? 'Terjadwal' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="fw-bold">
                <tr>
                    <td colspan="5" class="text-end">TOTAL:</td>
                    <td class="text-end">{{ number_format($totalKirim, 2, ',', '.') }} kg</td>
                    <td class="text-end">Rp {{ number_format($totalPendapatanKirim, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

    @elseif($jenis == 'sdm')
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Pekerja</th>
                    <th>Jenis Pekerjaan</th>
                    <th>Tanggal Kerja</th>
                    <th class="text-end">Upah Harian</th>
                    <th class="text-end">Durasi Kerja</th>
                    <th class="text-end">Total Gaji Upah</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGaji = 0; @endphp
                @foreach($dataLaporan as $index => $sdm)
                    @php $totalGaji += $sdm->total_gaji; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $sdm->nama_pekerja }}</td>
                        <td>{{ $sdm->jenis_pekerjaan }}</td>
                        <td>{{ date('d-m-Y', strtotime($sdm->tanggal_kerja)) }}</td>
                        <td class="text-end">Rp {{ number_format($sdm->upah_harian, 0, ',', '.') }}</td>
                        <td class="text-end">{{ $sdm->jumlah_hari }} Hari</td>
                        <td class="text-end fw-bold">Rp {{ number_format($sdm->total_gaji, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="fw-bold">
                <tr>
                    <td colspan="6" class="text-end">TOTAL :</td>
                    <td class="text-end text-danger">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    @elseif($jenis == 'pembibitan')
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal Pembibitan</th>
                    <th>Komoditas</th>
                    <th class="text-end">Jumlah Bibit Baru</th>
                    <th class="text-end">Jumlah Penyulaman</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBibit = 0; $totalSulam = 0; @endphp
                @foreach($dataLaporan as $index => $bibit)
                    @php 
                        $totalBibit += $bibit->jumlah_bibit;
                        $totalSulam += $bibit->jumlah_penyulaman;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($bibit->tanggal_pembibitan)) }}</td>
                        <td class="text-uppercase">{{ optional($bibit->komoditas)->nama_komoditas ?? '-' }}</td>
                        <td class="text-end">{{ number_format($bibit->jumlah_bibit, 0, ',', '.') }} Bibit</td>
                        <td class="text-end">{{ number_format($bibit->jumlah_penyulaman, 0, ',', '.') }} Sulam</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="fw-bold">
                <tr>
                    <td colspan="3" class="text-end">TOTAL REKAPITULASI PEMBIBITAN:</td>
                    <td class="text-end">{{ number_format($totalBibit, 0, ',', '.') }} Bibit</td>
                    <td class="text-end text-warning">{{ number_format($totalSulam, 0, ',', '.') }} Sulam</td>
                </tr>
            </tfoot>
        </table>

    @elseif($jenis == 'tanam')
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal Tanam</th>
                    <th>Area Kebun</th>
                    <th>Keterangan</th>
                    <th class="text-end">Jumlah Ditanam</th>
                </tr>
            </thead>
            <tbody>
                @php $totalTanam = 0; @endphp
                @foreach($dataLaporan as $index => $tanam)
                    @php $totalTanam += $tanam->jumlah_tanam; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($tanam->tanggal_tanam)) }}</td>
                        <td>{{ optional($tanam->area)->nama_area ?? '-' }}</td>
                        <td>{{ $tanam->keterangan ?: '-' }}</td>
                        <td class="text-end fw-bold">{{ number_format($tanam->jumlah_tanam, 0, ',', '.') }} Pokok</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="fw-bold">
                <tr>
                    <td colspan="4" class="text-end">TOTAL BIBIT YANG DITANAM:</td>
                    <td class="text-end">{{ number_format($totalTanam, 0, ',', '.') }} Pokok</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <!-- Tanda Tangan Penanggung Jawab -->
    <div class="row pt-5 justify-content-end">
        <div class="col-4 text-center">
            <p class="mb-5">Lampung Utara, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY') }}<br>Penanggung Jawab Perkebunan,</p>
            <p class="fw-bold border-top border-dark d-inline-block pt-1" style="width: 200px;">Haji Banyu (Pemilik)</p>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>
