<?php

namespace App\Http\Controllers;

use App\Models\Panen;
use App\Models\Operasional;
use App\Models\Pengiriman;
use App\Models\Sdm;
use App\Models\Pembibitan;
use App\Models\Tanam;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman utama filter laporan (Index)
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $start = null;
        $mulai = '';
        $akhir = date('Y-m-d'); // Default akhir adalah hari ini

        if ($filter == 'month') {
            $start = \Carbon\Carbon::now()->startOfMonth();
            $mulai = $start->format('Y-m-d');
            $akhir = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($filter == 'week') {
            $start = \Carbon\Carbon::now()->startOfWeek();
            $mulai = $start->format('Y-m-d');
            $akhir = \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d');
        } else {
            // Semua Waktu
            $mulai = '2000-01-01'; // Default tanggal mulai jauh di belakang
        }

        // Hitung total upah pekerja & total biaya operasional
        $queryUpah = Sdm::query();
        $queryOps = Operasional::query();
        $queryLog = \App\Models\LogAktivitas::with('user');

        if ($start) {
            $queryUpah->where('tanggal_bayar', '>=', $start);
            $queryOps->where('tanggal_perawatan', '>=', $start);
            $queryLog->where('created_at', '>=', $start);
        }

        $dbUpah = $queryUpah->sum('total_upah');
        $dbPemeliharaan = $queryOps->get()->sum('biaya_operasional');

        $totalUpah = $dbUpah;
        $totalPemeliharaan = $dbPemeliharaan;

        // Ambil log aktivitas audit sistem
        $logAktivitas = $queryLog->orderBy('created_at', 'desc')->paginate(10);

        // Security warnings list mockup
        $securityAlerts = [
            [
                'title' => 'Upaya Akses Ilegal',
                'desc' => '3 Kali kegagalan login dari IP 182.21.x.x (Jakarta)',
                'action' => 'BLOKIR IP',
                'type' => 'danger'
            ],
            [
                'title' => 'Pembaruan API',
                'desc' => 'Sistem telah diperbarui ke v2.4.1 secara otomatis.',
                'action' => null,
                'type' => 'info'
            ]
        ];

        $stats = [];
        if (Auth::user()->isOwner() && !isset($jenis)) {
            $stats = [
                'komoditas' => \App\Models\Komoditas::count(),
                'peralatan' => \App\Models\Peralatan::count(),
                'perlengkapan' => \App\Models\Perlengkapan::count(),
                'jadwal' => \App\Models\Jadwal::count(),
                'status' => \App\Models\Status::count(),
                'area' => \App\Models\AreaKebun::count(),
                'pegawai' => \App\Models\Pegawai::count(),
                'pengguna' => \App\Models\User::count(),
            ];
        }

        return view('laporan.index', compact('totalUpah', 'totalPemeliharaan', 'logAktivitas', 'securityAlerts', 'mulai', 'akhir', 'stats'));
    }

    /**
     * Tampilkan hasil pencarian filter laporan (Cari)
     */
    public function tampilkan(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:panen,operasional,pengiriman,sdm,pembibitan,tanam',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'jenis_laporan.required' => 'Jenis laporan wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal awal filter wajib ditentukan.',
            'tanggal_akhir.required' => 'Tanggal akhir filter wajib ditentukan.',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir tidak boleh mendahului tanggal awal.',
        ]);

        $jenis = $request->jenis_laporan;
        $mulai = $request->tanggal_mulai;
        $akhir = $request->tanggal_akhir;

        $dataLaporan = $this->queryLaporan($jenis, $mulai, $akhir);

        return view('laporan.index', compact('dataLaporan', 'jenis', 'mulai', 'akhir'));
    }

    /**
     * Cetak Laporan (Window Print)
     */
    public function cetak(Request $request)
    {
        $jenis = $request->query('jenis');
        $mulai = $request->query('mulai');
        $akhir = $request->query('akhir');

        $dataLaporan = $this->queryLaporan($jenis, $mulai, $akhir);

        return view('laporan.cetak', compact('dataLaporan', 'jenis', 'mulai', 'akhir'));
    }

    /**
     * Unduh Laporan PDF (Export PDF menggunakan DomPDF)
     */
    public function unduhPdf(Request $request)
    {
        $jenis = $request->query('jenis');
        $mulai = $request->query('mulai');
        $akhir = $request->query('akhir');

        $dataLaporan = $this->queryLaporan($jenis, $mulai, $akhir);

        $pdf = Pdf::loadView('laporan.pdf', compact('dataLaporan', 'jenis', 'mulai', 'akhir'))
                  ->setPaper('a4', 'landscape'); // Format lanskap agar tabel data terlihat rapi

        $namaFile = 'Laporan_' . ucfirst($jenis) . '_' . $mulai . '_s.d._' . $akhir . '.pdf';
        
        return $pdf->download($namaFile);
    }

    /**
     * Helper Query untuk mengambil data laporan sesuai jenis dan tanggal
     */
    private function queryLaporan($jenis, $mulai, $akhir)
    {
        $komoditasId = null;
        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) $komoditasId = 1;
            elseif (str_contains(Auth::user()->email, 'sawit')) $komoditasId = 2;
            elseif (str_contains(Auth::user()->email, 'lada')) $komoditasId = 3;
        }

        switch ($jenis) {
            case 'panen':
                $query = Panen::with('areaKebun', 'user')->whereBetween('tanggal', [$mulai, $akhir]);
                if ($komoditasId) $query->where('id_komoditas', $komoditasId);
                return $query->orderBy('tanggal', 'asc')->get();
            case 'operasional':
                $query = Operasional::with(['areaKebun', 'user', 'pegawai', 'peralatan', 'perlengkapan'])->whereBetween('tanggal_perawatan', [$mulai, $akhir]);
                if ($komoditasId) $query->where('id_komoditas', $komoditasId);
                return $query->orderBy('tanggal_perawatan', 'asc')->get();
            case 'pengiriman':
                $query = Pengiriman::with(['panen.areaKebun', 'komoditas', 'status_pengiriman'])->whereBetween('tanggal_pengiriman', [$mulai, $akhir]);
                if ($komoditasId) $query->where('id_komoditas', $komoditasId);
                return $query->orderBy('tanggal_pengiriman', 'asc')->get();
            case 'sdm':
                $query = Sdm::whereBetween('tanggal_bayar', [$mulai, $akhir]);
                if ($komoditasId) $query->where('id_komoditas', $komoditasId);
                return $query->orderBy('tanggal_bayar', 'asc')->get();
            case 'pembibitan':
                $query = Pembibitan::with('komoditas')->whereBetween('tanggal_pembibitan', [$mulai, $akhir]);
                if ($komoditasId) $query->where('id_komoditas', $komoditasId);
                return $query->orderBy('tanggal_pembibitan', 'asc')->get();
            case 'tanam':
                $query = Tanam::with('area')->whereBetween('tanggal_tanam', [$mulai, $akhir]);
                if ($komoditasId) $query->whereHas('area', function ($q) use ($komoditasId) {
                    $q->where('id_komoditas', $komoditasId);
                });
                return $query->orderBy('tanggal_tanam', 'asc')->get();
            default:
                return collect();
        }
    }
}
