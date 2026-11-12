<?php

namespace App\Http\Controllers;

use App\Models\AreaKebun;
use App\Models\Panen;
use App\Models\Operasional;
use App\Models\Sdm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isOwner()) {
            $filter = request()->get('filter', 'bulan');

            $startDate = null;
            $endDate = null;

            if ($filter == 'hari') {
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                $filterLabel = 'Pertanggal (Hari Ini)';
            } elseif ($filter == 'tahun') {
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                $filterLabel = 'Pertahun (Tahun Ini)';
            } else { // default: 'bulan'
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $filterLabel = 'Perbulan (Bulan Ini)';
            }

            // 1. Ringkasan Kinerja (Total Panen, Pengiriman, Operasional, SDM)
            $panenQuery = Panen::query();
            $pengirimanQuery = \App\Models\Pengiriman::query();
            $opsQuery = Operasional::query();
            $sdmQuery = Sdm::query();

            if ($startDate && $endDate) {
                $panenQuery->whereBetween('tanggal', [$startDate, $endDate]);
                $pengirimanQuery->whereBetween('tanggal_pengiriman', [$startDate, $endDate]);
                $opsQuery->whereBetween('tanggal_perawatan', [$startDate, $endDate]);
                $sdmQuery->whereBetween('tanggal_bayar', [$startDate, $endDate]);
            }

            $totalNettoPanen = $panenQuery->sum('netto_panen'); // dalam Kg
            $totalPengiriman = $pengirimanQuery->sum('jumlah_dikirim'); // dalam Kg
            $totalOpsKegiatan = $opsQuery->count();
            $totalUpahSdm = $sdmQuery->sum('total_upah');

            $summaryData = [
                'panen' => round($totalNettoPanen, 2),
                'pengiriman' => round($totalPengiriman, 2),
                'operasional' => $totalOpsKegiatan,
                'sdm' => $totalUpahSdm,
            ];

            // 2. Data Grafik Integrated Yield Comparison (Kopi, Lada, Sawit)
            if ($filter == 'hari') {
                $labelsBulan = [];
                for ($i = 6; $i >= 0; $i--) {
                    $labelsBulan[] = now()->locale('id')->subDays($i)->isoFormat('DD MMM');
                }
                
                $chartData = [
                    'kopi' => array_fill(0, 7, 0),
                    'lada' => array_fill(0, 7, 0),
                    'sawit' => array_fill(0, 7, 0)
                ];

                $panenHarian = Panen::select(
                    DB::raw('DATE(tanggal) as tanggal'),
                    'id_komoditas',
                    DB::raw('SUM(netto_panen) as total_netto')
                )
                ->where('tanggal', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('tanggal', 'id_komoditas')
                ->get();

                foreach ($panenHarian as $p) {
                    $dateStr = \Carbon\Carbon::parse($p->tanggal)->locale('id')->isoFormat('DD MMM');
                    $idx = array_search($dateStr, $labelsBulan);
                    if ($idx !== false) {
                        $komoditas = $p->id_komoditas == 1 ? 'kopi' : ($p->id_komoditas == 2 ? 'sawit' : 'lada');
                        $chartData[$komoditas][$idx] = round($p->total_netto / 1000, 2);
                    }
                }
            } elseif ($filter == 'tahun') {
                $labelsBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $chartData = [
                    'kopi' => array_fill(0, 12, 0),
                    'lada' => array_fill(0, 12, 0),
                    'sawit' => array_fill(0, 12, 0)
                ];

                $panenBulanan = Panen::select(
                    DB::raw('MONTH(tanggal) as bulan_num'),
                    'id_komoditas',
                    DB::raw('SUM(netto_panen) as total_netto')
                )
                ->whereYear('tanggal', date('Y'))
                ->groupBy('bulan_num', 'id_komoditas')
                ->get();

                foreach ($panenBulanan as $p) {
                    $idx = $p->bulan_num - 1;
                    $komoditas = $p->id_komoditas == 1 ? 'kopi' : ($p->id_komoditas == 2 ? 'sawit' : 'lada');
                    $chartData[$komoditas][$idx] = round($p->total_netto / 1000, 1);
                }
            } else { // default: 'bulan'
                $labelsBulan = ['Pekan 1', 'Pekan 2', 'Pekan 3', 'Pekan 4', 'Pekan 5'];
                $chartData = [
                    'kopi' => array_fill(0, 5, 0),
                    'lada' => array_fill(0, 5, 0),
                    'sawit' => array_fill(0, 5, 0)
                ];

                $panenMingguan = Panen::select(
                    DB::raw('FLOOR((DAY(tanggal) - 1) / 7) as pekan_idx'),
                    'id_komoditas',
                    DB::raw('SUM(netto_panen) as total_netto')
                )
                ->whereMonth('tanggal', date('m'))
                ->whereYear('tanggal', date('Y'))
                ->groupBy('pekan_idx', 'id_komoditas')
                ->get();

                foreach ($panenMingguan as $p) {
                    $idx = min(4, intval($p->pekan_idx));
                    $komoditas = $p->id_komoditas == 1 ? 'kopi' : ($p->id_komoditas == 2 ? 'sawit' : 'lada');
                    $chartData[$komoditas][$idx] += round($p->total_netto / 1000, 2);
                }
            }

            // 3. Real-time Manager Activity (Live Stream)
            $activitiesQuery = Operasional::with('areaKebun', 'pegawai', 'peralatan', 'perlengkapan');
            if ($startDate && $endDate) {
                $activitiesQuery->whereBetween('tanggal_perawatan', [$startDate, $endDate]);
            }
            $activities = $activitiesQuery->orderBy('tanggal_perawatan', 'desc')
                ->take(5)
                ->get();

            $managerActivities = [];
            foreach ($activities as $act) {
                $resources = [];
                if ($act->pupuk_digunakan > 0) $resources[] = round($act->pupuk_digunakan) . ' kg Pupuk';
                if ($act->obat_hama_digunakan > 0) $resources[] = round($act->obat_hama_digunakan) . ' L Obat';
                if ($act->bibit_baru > 0) $resources[] = $act->bibit_baru . ' Bibit Baru';
                if ($act->pergantian_tanaman > 0) $resources[] = $act->pergantian_tanaman . ' Penyulaman';
                
                if ($act->peralatan && strtolower($act->peralatan->nama_peralatan) !== 'tidak ada') {
                    $resources[] = $act->peralatan->nama_peralatan;
                }
                if ($act->perlengkapan && strtolower($act->perlengkapan->nama_perlengkapan) !== 'tidak ada') {
                    $resources[] = $act->perlengkapan->nama_perlengkapan;
                }
                
                $resourceStr = count($resources) > 0 ? implode(', ', $resources) : 'Pemeliharaan Rutin';
                
                $managerActivities[] = [
                    'manager' => optional($act->pegawai)->nama_pegawai ?? 'Pengurus',
                    'action' => $act->jenis_perawatan ?? 'Pemeliharaan Lahan',
                    'location' => optional($act->areaKebun)->nama_lahan ?? 'Lahan Kebun',
                    'resources' => $resourceStr,
                    'status' => $act->status_operasional == 'Selesai' ? 'SELESAI' : ($act->status_operasional == 'Proses' ? 'PROSES' : 'TERJADWAL')
                ];
            }

            // 4. Aktivitas Pengiriman (Menggantikan Critical Alerts)
            $pengirimanQueryList = \App\Models\Pengiriman::with('panen.areaKebun', 'status_pengiriman');
            if ($startDate && $endDate) {
                $pengirimanQueryList->whereBetween('tanggal_pengiriman', [$startDate, $endDate]);
            }
            $pengirimans = $pengirimanQueryList->orderBy('tanggal_pengiriman', 'desc')
                ->take(4)
                ->get();

            $recentPengiriman = [];
            foreach ($pengirimans as $kirim) {
                $status = strtolower(optional($kirim->status_pengiriman)->nama_status ?? '');
                $type = 'secondary';
                $icon = 'fa-truck';
                if (str_contains($status, 'selesai') || str_contains($status, 'terkirim')) {
                    $type = 'success';
                    $icon = 'fa-check-circle';
                } elseif (str_contains($status, 'batal') || str_contains($status, 'tolak')) {
                    $type = 'danger';
                    $icon = 'fa-times-circle';
                } elseif (str_contains($status, 'proses')) {
                    $type = 'warning';
                    $icon = 'fa-truck-fast';
                }

                $recentPengiriman[] = [
                    'title' => 'Pengiriman ' . number_format($kirim->jumlah_dikirim, 0, ',', '.') . ' kg',
                    'desc' => $kirim->keterangan ?: 'Ke pabrik tujuan',
                    'time' => strtoupper(optional($kirim->status_pengiriman)->nama_status ?? 'TERJADWAL') . ' • ' . \Carbon\Carbon::parse($kirim->tanggal_pengiriman)->diffForHumans(),
                    'type' => $type,
                    'icon' => $icon
                ];
            }

            return view('dashboard_owner', compact(
                'summaryData',
                'labelsBulan',
                'chartData',
                'managerActivities',
                'recentPengiriman',
                'filter',
                'filterLabel'
            ));
        } elseif (str_contains($user->email, 'kopi') || str_contains($user->email, 'lada') || str_contains($user->email, 'sawit')) {
            // Redirect pengurus ke halaman area kebun yang sekarang berfungsi sebagai dashboard
            return redirect()->route('area-kebun.index');
        }
    }

    // Helper menerjemahkan bulan ke Bahasa Indonesia
    private function terjemahkanBulan($bulanInggris)
    {
        $daftarBulan = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];

        return $daftarBulan[$bulanInggris] ?? $bulanInggris;
    }
}
