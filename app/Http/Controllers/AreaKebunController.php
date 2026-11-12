<?php

namespace App\Http\Controllers;

use App\Models\AreaKebun;
use App\Models\LogAktivitas;
use App\Models\Panen;
use App\Models\Pengiriman;
use App\Models\Sdm;
use App\Models\Pembibitan;
use App\Models\Tanam;
use App\Models\Operasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaKebunController extends Controller
{
    /**
     * Tampilkan daftar area kebun (Index)
     */
    public function index(Request $request)
    {
        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                // PENGURUS KEBUN KOPI
                $query = AreaKebun::where('id_komoditas', 1);
                
                if ($request->has('cari') && $request->cari != '') {
                    $query->where('nama_area', 'like', '%' . $request->cari . '%');
                }
                
                $dataLahan = $query->orderBy('nama_area', 'asc')->paginate(10);
                
                // Enrich with mockup values
                foreach ($dataLahan as $lahan) {
                    $statusMapping = [
                        'produktif' => 'Healthy',
                        'perawatan' => 'Growing',
                        'replant' => 'Replanting'
                    ];
                    $lahan->status_kesehatan = $statusMapping[$lahan->status_lahan] ?? 'Healthy';
                    $lahan->next_harvest = date('d M Y', strtotime($lahan->estimasi_panen));
                    $lahan->varietas = str_contains($lahan->nama_lahan, 'Arabika') ? 'Arabica Typica' : 'Robusta Gayo';
                }
                
                $totalLuas = AreaKebun::where('id_komoditas', 1)->sum('luas_lahan') ?: 0;
                $totalBlok = AreaKebun::where('id_komoditas', 1)->count() ?: 0;
                
                $totalPanen = Panen::where('id_komoditas', 1)->sum('netto_panen') ?: 0;
                $totalPengiriman = Pengiriman::where('id_komoditas', 1)->sum('jumlah_dikirim') ?: 0;
                $totalSdm = Sdm::where('id_komoditas', 1)->sum('total_upah') ?: 0;
                $totalBibitMasuk = Pembibitan::where('id_komoditas', 1)->sum('jumlah_bibit') ?: 0;
                $totalTanam = Tanam::whereHas('area', function($q) { $q->where('id_komoditas', 1); })->sum('jumlah_tanam') ?: 0;
                $totalPembibitan = max(0, $totalBibitMasuk - $totalTanam);
                
                $totalPemupukan = Operasional::where('id_komoditas', 1)
                    ->whereIn('jenis_perawatan', ['Pemupukan', 'Pemupukan dan Obat Hama'])
                    ->count();
                $totalObatHama = Operasional::where('id_komoditas', 1)
                    ->whereIn('jenis_perawatan', ['Obat Hama', 'Pemupukan dan Obat Hama'])
                    ->count();
                
                return view('area_kebun.index_kopi', compact(
                    'dataLahan',
                    'totalLuas',
                    'totalBlok',
                    'totalPanen',
                    'totalPengiriman',
                    'totalSdm',
                    'totalPembibitan',
                    'totalTanam',
                    'totalPemupukan',
                    'totalObatHama'
                ));
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                // PENGURUS KEBUN LADA
                $query = AreaKebun::where('id_komoditas', 3);
                
                if ($request->has('cari') && $request->cari != '') {
                    $query->where('nama_area', 'like', '%' . $request->cari . '%');
                }
                
                if ($request->has('status') && $request->status != '') {
                    if ($request->status == 'Produktif') {
                        $query->where('status_area', 'Aktif');
                    } elseif ($request->status == 'Replanting') {
                        $query->where('status_area', 'Tidak Aktif');
                    }
                }
                
                $dataLahan = $query->orderBy('nama_area', 'asc')->paginate(10);
                
                // Enrich with Lada-specific values
                foreach ($dataLahan as $lahan) {
                    $statusMapping = [
                        'produktif' => 'Produktif',
                        'perawatan' => 'Produktif',
                        'replant' => 'Replanting'
                    ];
                    $lahan->status_kesehatan = $statusMapping[$lahan->status_lahan] ?? 'Produktif';
                    $lahan->next_harvest = date('d M Y', strtotime($lahan->estimasi_panen));
                    $lahan->tiang_panjat = $lahan->jumlah_tanaman;
                }
                
                $totalLuas = AreaKebun::where('id_komoditas', 3)->sum('luas_lahan') ?: 0;
                $totalBlok = AreaKebun::where('id_komoditas', 3)->count() ?: 0;
                
                $totalPanen = Panen::where('id_komoditas', 3)->sum('netto_panen') ?: 0;
                $totalPengiriman = Pengiriman::where('id_komoditas', 3)->sum('jumlah_dikirim') ?: 0;
                $totalSdm = Sdm::where('id_komoditas', 3)->sum('total_upah') ?: 0;
                $totalBibitMasuk = Pembibitan::where('id_komoditas', 3)->sum('jumlah_bibit') ?: 0;
                $totalTanam = Tanam::whereHas('area', function($q) { $q->where('id_komoditas', 3); })->sum('jumlah_tanam') ?: 0;
                $totalPembibitan = max(0, $totalBibitMasuk - $totalTanam);
                
                $totalPemupukan = Operasional::where('id_komoditas', 3)
                    ->whereIn('jenis_perawatan', ['Pemupukan', 'Pemupukan dan Obat Hama'])
                    ->count();
                $totalObatHama = Operasional::where('id_komoditas', 3)
                    ->whereIn('jenis_perawatan', ['Obat Hama', 'Pemupukan dan Obat Hama'])
                    ->count();
                
                return view('area_kebun.index_lada', compact(
                    'dataLahan',
                    'totalLuas',
                    'totalBlok',
                    'totalPanen',
                    'totalPengiriman',
                    'totalSdm',
                    'totalPembibitan',
                    'totalTanam',
                    'totalPemupukan',
                    'totalObatHama'
                ));
            } else {
                // PENGURUS KEBUN SAWIT
                $query = AreaKebun::where('id_komoditas', 2);
                
                if ($request->has('cari') && $request->cari != '') {
                    $query->where('nama_area', 'like', '%' . $request->cari . '%');
                }
                
                // Filter by status (Harvesting, Growing, Replanting)
                if ($request->has('status') && $request->status != '') {
                    if ($request->status == 'Harvesting' || $request->status == 'Growing') {
                        $query->where('status_area', 'Aktif');
                    } elseif ($request->status == 'Replanting') {
                        $query->where('status_area', 'Tidak Aktif');
                    }
                }
                
                $dataLahan = $query->orderBy('nama_area', 'asc')->paginate(10);
                
                // Enrich with mockup columns
                foreach ($dataLahan as $lahan) {
                    if ($lahan->status_lahan == 'produktif') {
                        $lahan->status_kesehatan = 'Harvesting';
                        $lahan->next_harvest = date('d M Y', strtotime($lahan->estimasi_panen));
                    } elseif ($lahan->status_lahan == 'perawatan') {
                        $lahan->status_kesehatan = 'Growing';
                        $lahan->next_harvest = date('d M Y', strtotime($lahan->estimasi_panen));
                    } else {
                        $lahan->status_kesehatan = 'Replanting';
                        $lahan->next_harvest = 'N/A';
                    }
                }
                
                $totalLuas = AreaKebun::where('id_komoditas', 2)->sum('luas_lahan') ?: 0;
                $totalBlok = AreaKebun::where('id_komoditas', 2)->count() ?: 0;
                
                $totalPanen = Panen::where('id_komoditas', 2)->sum('netto_panen') ?: 0;
                $totalPengiriman = Pengiriman::where('id_komoditas', 2)->sum('jumlah_dikirim') ?: 0;
                $totalSdm = Sdm::where('id_komoditas', 2)->sum('total_upah') ?: 0;
                $totalBibitMasuk = Pembibitan::where('id_komoditas', 2)->sum('jumlah_bibit') ?: 0;
                $totalTanam = Tanam::whereHas('area', function($q) { $q->where('id_komoditas', 2); })->sum('jumlah_tanam') ?: 0;
                $totalPembibitan = max(0, $totalBibitMasuk - $totalTanam);
                
                $totalPemupukan = Operasional::where('id_komoditas', 2)
                    ->whereIn('jenis_perawatan', ['Pemupukan', 'Pemupukan dan Obat Hama'])
                    ->count();
                $totalObatHama = Operasional::where('id_komoditas', 2)
                    ->whereIn('jenis_perawatan', ['Obat Hama', 'Pemupukan dan Obat Hama'])
                    ->count();
                
                return view('area_kebun.index_pengurus', compact(
                    'dataLahan',
                    'totalLuas',
                    'totalBlok',
                    'totalPanen',
                    'totalPengiriman',
                    'totalSdm',
                    'totalPembibitan',
                    'totalTanam',
                    'totalPemupukan',
                    'totalObatHama'
                ));
            }
        }

        $query = AreaKebun::query();

        // Fitur Pencarian Nama Lahan
        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_area', 'like', '%' . $request->cari . '%');
        }

        // Fitur Filter Jenis Tanaman
        if ($request->has('jenis_tanaman') && $request->jenis_tanaman != '') {
            $map = ['kopi' => 1, 'sawit' => 2, 'lada' => 3];
            $query->where('id_komoditas', $map[strtolower($request->jenis_tanaman)] ?? $request->jenis_tanaman);
        }

        $dataLahan = $query->orderBy('created_at', 'desc')->paginate(10);

        // Perhitungan Statistik Penggunaan Lahan
        $totalKopiArea = AreaKebun::where('id_komoditas', 1)->sum('luas_lahan') ?? 0;
        $totalLadaArea = AreaKebun::where('id_komoditas', 3)->sum('luas_lahan') ?? 0;
        $totalSawitArea = AreaKebun::where('id_komoditas', 2)->sum('luas_lahan') ?? 0;

        $totalArea = $totalKopiArea + $totalLadaArea + $totalSawitArea;

        // Memperkaya data lahan dengan metrik dinamis
        foreach ($dataLahan as $lahan) {
            // Ambil tanggal inspeksi terakhir dari operasional lahan
            $lastOp = \App\Models\Operasional::where('id_area', $lahan->id_area)
                ->orderBy('tanggal_perawatan', 'desc')
                ->first();
            
            $lahan->inspeksi_terakhir = $lastOp 
                ? \Carbon\Carbon::parse($lastOp->tanggal_perawatan)->locale('id')->isoFormat('DD MMM YYYY') 
                : '12 Okt 2023'; // Default fallback

            // Hitung persentase produktivitas berdasarkan status lahan
            if ($lahan->status_lahan == 'produktif') {
                $lahan->produktivitas = rand(88, 95);
                $lahan->status_kesehatan = 'OPTIMAL';
            } elseif ($lahan->status_lahan == 'perawatan') {
                $lahan->produktivitas = rand(70, 82);
                $lahan->status_kesehatan = 'STABIL';
            } else {
                $lahan->produktivitas = rand(40, 52);
                $lahan->status_kesehatan = 'PERLU TINDAKAN';
            }
        }

        // Tiga metrik footer
        $totalNettoPanen = \App\Models\Panen::sum('netto_panen');
        $prediksiPanen = $totalNettoPanen > 0 ? round($totalNettoPanen / 1000, 1) : 128.4;
        
        $efisiensiIrigasi = 94.2; // Nilai dummy efisiensi irigasi sensor IoT
        $pekerjaAktif = 342; // Jumlah pekerja lapangan

        return view('area_kebun.index', compact(
            'dataLahan',
            'totalKopiArea',
            'totalLadaArea',
            'totalSawitArea',
            'totalArea',
            'prediksiPanen',
            'efisiensiIrigasi',
            'pekerjaAktif'
        ));
    }

    /**
     * Tampilkan detail area kebun (Show)
     */
    public function show($id)
    {
        $lahan = AreaKebun::findOrFail($id);
        return view('area_kebun.show', compact('lahan'));
    }

    /**
     * Tampilkan formulir tambah area kebun (Create)
     */
    public function create()
    {
        return view('area_kebun.create');
    }

    /**
     * Simpan area kebun baru (Store)
     */
    public function store(Request $request)
    {
        // Validasi Form input
        $request->validate([
            'nama_lahan' => 'required|string|max:255',
            'jenis_tanaman' => 'required|in:kopi,sawit,lada',
            'luas_hektar' => 'required|numeric|min:0.01',
            'jumlah_tanaman' => 'required|integer|min:1',
            'usia_tanaman' => 'required|integer|min:0',
            'tanggal_tanam' => 'required|date',
            'estimasi_panen' => 'nullable|date',
            'status_lahan' => 'required|in:produktif,perawatan,peremajaan,baru_tanam',
        ], [
            'nama_lahan.required' => 'Nama lahan wajib diisi.',
            'jenis_tanaman.required' => 'Jenis tanaman komoditas wajib dipilih.',
            'luas_hektar.required' => 'Luas lahan (hektar) wajib diisi.',
            'luas_hektar.numeric' => 'Luas lahan harus berupa angka desimal.',
            'jumlah_tanaman.required' => 'Jumlah tanaman wajib diisi.',
            'jumlah_tanaman.integer' => 'Jumlah tanaman harus berupa bilangan bulat.',
            'usia_tanaman.required' => 'Usia tanaman wajib diisi.',
            'tanggal_tanam.required' => 'Tanggal penanaman wajib diisi.',
            'estimasi_panen.after_or_equal' => 'Tanggal estimasi panen tidak boleh mendahului tanggal tanam.',
            'status_lahan.required' => 'Status lahan kebun wajib ditentukan.',
        ]);

        $data = $request->except('keterangan');
        if ($request->has('usia_tanaman') && $request->usia_tanaman !== null && $request->usia_tanaman !== '') {
            $data['tanggal_tanam'] = \Carbon\Carbon::now()->subMonths((int)$request->usia_tanaman)->toDateString();
        }

        // Hitung estimasi panen otomatis
        if (!empty($data['tanggal_tanam'])) {
            $tanggalTanam = \Carbon\Carbon::parse($data['tanggal_tanam']);
            
            // Estimasi panen berdasarkan jenis tanaman (Kopi 4 tahun, Sawit 4 tahun, Lada 3 tahun)
            $bulanEstimasi = match($data['jenis_tanaman']) {
                'kopi' => 48,
                'sawit' => 48,
                'lada' => 36,
                default => 48
            };
            
            $data['estimasi_panen'] = $tanggalTanam->copy()->addMonths($bulanEstimasi)->format('Y-m-d');
        }

        // Simpan ke database
        $lahan = AreaKebun::create($data);

        // Catat ke Log Aktivitas Pengguna
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Area Kebun',
            'deskripsi' => 'Pengguna ' . Auth::user()->name . ' menambahkan lahan baru bernama: ' . $lahan->nama_lahan . ' (' . ucfirst($lahan->jenis_tanaman) . ')'
        ]);

        return redirect()->route('area-kebun.index')
            ->with('sukses', 'Area kebun berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit area kebun (Edit)
     */
    public function edit($id)
    {
        $lahan = AreaKebun::findOrFail($id);
        return view('area_kebun.edit', compact('lahan'));
    }

    /**
     * Perbarui area kebun (Update)
     */
    public function update(Request $request, $id)
    {
        $lahan = AreaKebun::findOrFail($id);

        // Validasi Form
        $request->validate([
            'nama_lahan' => 'required|string|max:255',
            'jenis_tanaman' => 'required|in:kopi,sawit,lada',
            'luas_hektar' => 'required|numeric|min:0.01',
            'jumlah_tanaman' => 'required|integer|min:1',
            'usia_tanaman' => 'required|integer|min:0',
            'tanggal_tanam' => 'required|date',
            'estimasi_panen' => 'nullable|date',
            'status_lahan' => 'required|in:produktif,perawatan,peremajaan,baru_tanam',
        ]);

        // Update data
        $data = $request->except('keterangan');
        if ($request->has('usia_tanaman') && $request->usia_tanaman !== null && $request->usia_tanaman !== '') {
            $data['tanggal_tanam'] = \Carbon\Carbon::now()->subMonths((int)$request->usia_tanaman)->toDateString();
        }

        if (!empty($data['tanggal_tanam'])) {
            $tanggalTanam = \Carbon\Carbon::parse($data['tanggal_tanam']);
            $bulanEstimasi = match($data['jenis_tanaman']) {
                'kopi' => 48,
                'sawit' => 48,
                'lada' => 36,
                default => 48
            };
            $data['estimasi_panen'] = $tanggalTanam->copy()->addMonths($bulanEstimasi)->format('Y-m-d');
        }

        $lahan->update($data);

        // Catat ke Log Aktivitas Pengguna
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Area Kebun',
            'deskripsi' => 'Pengguna ' . Auth::user()->name . ' mengubah detail informasi lahan kebun: ' . $lahan->nama_lahan
        ]);

        return redirect()->route('area-kebun.index')
            ->with('sukses', 'Informasi area kebun berhasil diperbarui.');
    }

    /**
     * Hapus area kebun (Destroy)
     */
    public function destroy($id)
    {
        $lahan = AreaKebun::findOrFail($id);
        $namaLahan = $lahan->nama_lahan;
        
        try {
            $lahan->delete();

            // Catat ke Log Aktivitas Pengguna
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Area Kebun',
                'deskripsi' => 'Pengguna ' . Auth::user()->name . ' menghapus data area kebun: ' . $namaLahan
            ]);

            return redirect()->route('area-kebun.index')
                ->with('sukses', 'Area kebun berhasil dihapus dari sistem.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('area-kebun.index')
                    ->with('gagal', 'Gagal menghapus! Area kebun ini masih memiliki data terkait (seperti panen atau operasional). Silakan hapus data terkait terlebih dahulu.');
            }
            return redirect()->route('area-kebun.index')
                ->with('gagal', 'Terjadi kesalahan saat menghapus data area kebun.');
        }
    }
}
