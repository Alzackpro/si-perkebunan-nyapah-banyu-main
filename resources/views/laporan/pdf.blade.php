<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ ucfirst($jenis) }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #135d37;
            padding-bottom: 8px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #135d37;
            text-transform: uppercase;
        }

        .header-sub {
            font-size: 10px;
            color: #666666;
            margin-top: 3px;
        }

        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0;
            color: #2d3748;
        }

        .meta-info {
            font-size: 10px;
            color: #666666;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th {
            background-color: #135d37;
            color: #ffffff;
            font-weight: bold;
            padding: 8px;
            text-align: left;
            border: 1px solid #135d37;
        }

        table td {
            padding: 7px 8px;
            border: 1px solid #dddddd;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .fw-bold {
            font-weight: bold;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .signature-section {
            margin-top: 30px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            width: 250px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="header-title">Perkebunan Nyapah Banyu</div>
    <div class="header-sub">
        Kec. Abung Barat, Kabupaten Lampung Utara, Lampung, Indonesia<br>
        Telepon: +62 822-8123-XXXX | Email: info@nyapahbanyu.com
    </div>
</div>

<div class="doc-title">
    Laporan Data {{ strtoupper($jenis) }}
</div>

<div class="meta-info">
    <strong>Periode Filter:</strong> {{ date('d-m-Y', strtotime($mulai)) }} s.d. {{ date('d-m-Y', strtotime($akhir)) }}<br>
    <strong>Tanggal Unduh:</strong> {{ date('d-m-Y H:i') }}
</div>

<!-- DATA TABEL LAPORAN -->
@if($jenis == 'panen')
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">Tanggal Panen</th>
                <th style="width: 25%;">Lahan Kebun</th>
                <th style="width: 10%;">Komoditas</th>
                <th class="text-end" style="width: 12%;">Berat Gross</th>
                <th class="text-end" style="width: 12%;">Berat Netto</th>
                <th class="text-end" style="width: 11%;">Harga Jual</th>
                <th class="text-end" style="width: 14%;">Total Pendapatan</th>
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
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($panen->tanggal_panen)) }}</td>
                    <td>{{ $panen->areaKebun->nama_lahan }}</td>
                    <td class="text-uppercase">{{ $panen->jenis_tanaman }}</td>
                    <td class="text-end">{{ number_format($panen->jumlah_panen_gross, 2, ',', '.') }} kg</td>
                    <td class="text-end fw-bold">{{ number_format($panen->jumlah_netto, 2, ',', '.') }} kg</td>
                    <td class="text-end">Rp {{ number_format($panen->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold">Rp {{ number_format($panen->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="bg-light fw-bold">
                <td colspan="4" class="text-end">TOTAL REKAPITULASI:</td>
                <td class="text-end">{{ number_format($totalGross, 2, ',', '.') }} kg</td>
                <td class="text-end">{{ number_format($totalNetto, 2, ',', '.') }} kg</td>
                <td></td>
                <td class="text-end">Rp {{ number_format($totalPend, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

@elseif($jenis == 'operasional')
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Lahan Kebun</th>
                <th style="width: 15%;">Jenis Perawatan</th>
                <th style="width: 15%;">Keterangan</th>
                <th style="width: 15%;">Pegawai Pelaksana</th>
                <th style="width: 15%;">Peralatan & Perlengkapan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataLaporan as $index => $ops)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
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
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 13%;">Tanggal Kirim</th>
                <th style="width: 25%;">Lahan Asal</th>
                <th style="width: 10%;">Komoditas</th>
                <th style="width: 15%;">Keterangan</th>
                <th class="text-end" style="width: 11%;">Berat Kirim</th>
                <th class="text-end" style="width: 14%;">Total Penjualan</th>
                <th style="width: 8%;">Status</th>
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
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($kirim->tanggal_pengiriman)) }}</td>
                    <td>{{ optional(optional($kirim->panen)->areaKebun)->nama_lahan ?? '-' }}</td>
                    <td class="text-uppercase">{{ optional($kirim->komoditas)->nama_komoditas ?? '-' }}</td>
                    <td>{{ $kirim->keterangan ?: '-' }}</td>
                    <td class="text-end fw-bold">{{ number_format($kirim->jumlah_dikirim, 2, ',', '.') }} kg</td>
                    <td class="text-end fw-bold">Rp {{ number_format($kirim->total_penjualan, 0, ',', '.') }}</td>
                    <td class="text-uppercase">{{ optional($kirim->status_pengiriman)->nama_status ?? 'Terjadwal' }}</td>
                </tr>
            @endforeach
            <tr class="bg-light fw-bold">
                <td colspan="5" class="text-end">TOTAL TONASE & PENDAPATAN:</td>
                <td class="text-end">{{ number_format($totalKirim, 2, ',', '.') }} kg</td>
                <td class="text-end">Rp {{ number_format($totalPendapatanKirim, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

@elseif($jenis == 'sdm')
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Pekerja</th>
                <th style="width: 20%;">Jenis Pekerjaan</th>
                <th style="width: 15%;">Tanggal Kerja</th>
                <th class="text-end" style="width: 12%;">Upah Harian</th>
                <th class="text-end" style="width: 13%;">Durasi Kerja</th>
                <th class="text-end" style="width: 15%;">Total Upah Gaji</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGaji = 0; @endphp
            @foreach($dataLaporan as $index => $sdm)
                @php $totalGaji += $sdm->total_gaji; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $sdm->nama_pekerja }}</td>
                    <td>{{ $sdm->jenis_pekerjaan }}</td>
                    <td>{{ date('d-m-Y', strtotime($sdm->tanggal_kerja)) }}</td>
                    <td class="text-end">Rp {{ number_format($sdm->upah_harian, 0, ',', '.') }}</td>
                    <td class="text-end">{{ $sdm->jumlah_hari }} Hari</td>
                    <td class="text-end fw-bold">Rp {{ number_format($sdm->total_gaji, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="bg-light fw-bold">
                <td colspan="6" class="text-end">TOTAL PENGELUARAN GAJI PEKERJA:</td>
                <td class="text-end">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

@elseif($jenis == 'pembibitan')
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Tanggal Pembibitan</th>
                <th style="width: 25%;">Komoditas</th>
                <th class="text-end" style="width: 25%;">Jumlah Bibit Baru</th>
                <th class="text-end" style="width: 25%;">Jumlah Penyulaman</th>
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
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($bibit->tanggal_pembibitan)) }}</td>
                    <td class="text-uppercase">{{ optional($bibit->komoditas)->nama_komoditas ?? '-' }}</td>
                    <td class="text-end">{{ number_format($bibit->jumlah_bibit, 0, ',', '.') }} Bibit</td>
                    <td class="text-end">{{ number_format($bibit->jumlah_penyulaman, 0, ',', '.') }} Sulam</td>
                </tr>
            @endforeach
            <tr class="bg-light fw-bold">
                <td colspan="3" class="text-end">TOTAL REKAPITULASI PEMBIBITAN:</td>
                <td class="text-end">{{ number_format($totalBibit, 0, ',', '.') }} Bibit</td>
                <td class="text-end">{{ number_format($totalSulam, 0, ',', '.') }} Sulam</td>
            </tr>
        </tbody>
    </table>

@elseif($jenis == 'tanam')
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Tanggal Tanam</th>
                <th style="width: 35%;">Area Kebun</th>
                <th style="width: 20%;">Keterangan</th>
                <th class="text-end" style="width: 20%;">Jumlah Ditanam</th>
            </tr>
        </thead>
        <tbody>
            @php $totalTanam = 0; @endphp
            @foreach($dataLaporan as $index => $tanam)
                @php $totalTanam += $tanam->jumlah_tanam; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($tanam->tanggal_tanam)) }}</td>
                    <td>{{ optional($tanam->area)->nama_area ?? '-' }}</td>
                    <td>{{ $tanam->keterangan ?: '-' }}</td>
                    <td class="text-end fw-bold">{{ number_format($tanam->jumlah_tanam, 0, ',', '.') }} Pokok</td>
                </tr>
            @endforeach
            <tr class="bg-light fw-bold">
                <td colspan="4" class="text-end">TOTAL BIBIT YANG DITANAM:</td>
                <td class="text-end">{{ number_format($totalTanam, 0, ',', '.') }} Pokok</td>
            </tr>
        </tbody>
    </table>
@endif

<div class="signature-section">
    <div class="signature-box">
        <p>Lampung Utara, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY') }}<br>Penanggung Jawab Perkebunan,</p>
        <div style="margin-top: 50px; font-weight: bold; border-top: 1px solid #333333; display: inline-block; width: 180px; padding-top: 5px;">
            Haji Banyu (Pemilik)
        </div>
    </div>
</div>

</body>
</html>
