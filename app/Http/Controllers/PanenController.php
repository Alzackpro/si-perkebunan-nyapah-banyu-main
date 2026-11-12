<?php

namespace App\Http\Controllers;

use App\Models\Panen;
use App\Models\Jadwal;
use App\Models\Komoditas;
use App\Models\AreaKebun;
use App\Models\Peralatan;
use App\Models\Perlengkapan;
use App\Models\LogAktivitas;
use App\Models\Status;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanenController extends Controller
{
    public function index(Request $request)
    {
        $idPanenDikirim = Pengiriman::whereNotNull('id_panen')->pluck('id_panen')->toArray();

        $query = Panen::with(['jadwal', 'komoditas', 'area', 'peralatan', 'perlengkapan', 'status_panen']);

        // Filter based on Pengurus role
        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $query->where('id_komoditas', 1);
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $query->where('id_komoditas', 2);
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $query->where('id_komoditas', 3);
            }
        }

        if ($request->has('cari') && $request->cari != '') {
            $query->where('keterangan', 'like', '%' . $request->cari . '%');
        }

        $dataPanen = $query->orderBy('tanggal', 'desc')->paginate(10);

        // Agregat Sederhana
        $totalGross = $query->sum('gross_panen');
        $totalNetto = $query->sum('netto_panen');

        $listStatus = Status::all();

        return view('panen.index', compact('dataPanen', 'totalGross', 'totalNetto', 'listStatus'));
    }

    public function create()
    {
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $queryArea = AreaKebun::query();
        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $queryArea->where('id_komoditas', 1);
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $queryArea->where('id_komoditas', 2);
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $queryArea->where('id_komoditas', 3);
            }
        }

        // Filter area yang sudah bisa produktif (status_area = 'Aktif' ATAU estimasi_panen <= hari ini)
        $area = $queryArea->get()->filter(function ($lahan) {
            $isProduktif = $lahan->status_area === 'Aktif' || \Carbon\Carbon::parse($lahan->estimasi_panen)->isPast() || \Carbon\Carbon::parse($lahan->estimasi_panen)->isToday();
            if (!$isProduktif) return false;
            
            // Cek cooldown panen
            $lastPanen = \App\Models\Panen::where('id_area', $lahan->id_area)->orderBy('tanggal', 'desc')->first();
            if ($lastPanen) {
                $lastDate = \Carbon\Carbon::parse($lastPanen->tanggal);
                $now = \Carbon\Carbon::now();
                
                if ($lahan->id_komoditas == 1 || $lahan->id_komoditas == 3) { // Kopi & Lada (6 bulan)
                    if ($lastDate->copy()->addMonths(6)->isAfter($now)) return false;
                } elseif ($lahan->id_komoditas == 2) { // Sawit (14 hari)
                    if ($lastDate->copy()->addDays(14)->isAfter($now)) return false;
                }
            }
            
            return true;
        });
        
        $peralatan = Peralatan::all();
        $perlengkapan = Perlengkapan::all();
        
        // Auto-fill values based on Pengurus login
        $defaultKomoditas = null;
        $defaultJadwal = null;
        $defaultArea = null;

        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $defaultKomoditas = 1; $defaultJadwal = 1; $defaultArea = 1;
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $defaultKomoditas = 2; $defaultJadwal = 2; $defaultArea = 2;
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $defaultKomoditas = 3; $defaultJadwal = 3; $defaultArea = 3;
            }
        }

        return view('panen.create', compact('jadwal', 'komoditas', 'area', 'peralatan', 'perlengkapan', 'defaultKomoditas', 'defaultJadwal', 'defaultArea'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_area' => 'required|exists:area_kebun,id_area',
            'id_peralatan' => 'nullable|array',
            'id_peralatan.*' => 'exists:peralatan,id_peralatan',
            'id_perlengkapan' => 'nullable|array',
            'id_perlengkapan.*' => 'exists:perlengkapan,id_perlengkapan',
            'tanggal' => 'required|date',
            'gross_panen' => 'required|numeric|min:0.01',
            'netto_panen' => 'required|numeric|min:0.01|lte:gross_panen',
            'keterangan' => 'nullable|string',
            
            // Validasi Upah Pekerja (Opsional, sekarang array)
            'nama_pekerja' => 'nullable|array',
            'nama_pekerja.*' => 'nullable|string|max:100',
            'jumlah_hari' => 'nullable|array',
            'jumlah_hari.*' => 'nullable|integer|min:1',
            'tarif_harian' => 'nullable|array',
            'tarif_harian.*' => 'nullable|numeric|min:0',
        ], [
            'netto_panen.lte' => 'Netto panen tidak boleh melebihi gross panen.',
        ]);

        // Cek cooldown panen di sisi server
        $lahan = AreaKebun::find($request->id_area);
        if ($lahan) {
            $lastPanen = \App\Models\Panen::where('id_area', $lahan->id_area)->orderBy('tanggal', 'desc')->first();
            if ($lastPanen) {
                $lastDate = \Carbon\Carbon::parse($lastPanen->tanggal);
                $requestDate = \Carbon\Carbon::parse($request->tanggal);
                
                if ($lahan->id_komoditas == 1 || $lahan->id_komoditas == 3) {
                    if ($lastDate->copy()->addMonths(6)->isAfter($requestDate)) {
                        return back()->withInput()->with('gagal', 'Lahan ini belum bisa dipanen. Jeda waktu panen untuk komoditas ini minimal 6 bulan dari panen terakhir ('. $lastDate->format('d M Y') .').');
                    }
                } elseif ($lahan->id_komoditas == 2) {
                    if ($lastDate->copy()->addDays(14)->isAfter($requestDate)) {
                        return back()->withInput()->with('gagal', 'Lahan ini belum bisa dipanen. Jeda waktu panen untuk Sawit minimal 2 minggu dari panen terakhir ('. $lastDate->format('d M Y') .').');
                    }
                }
            }
        }

        $data = $request->all();
        // Boleh dikosongkan, fallback ke 1 (Tidak Ada)
        $data['id_peralatan'] = $request->id_peralatan ? implode(',', $request->id_peralatan) : '1';
        $data['id_perlengkapan'] = $request->id_perlengkapan ? implode(',', $request->id_perlengkapan) : '1';
        $data['id_status'] = 1; // Default status: Disimpan (di gudang)

        $panen = Panen::create($data);

        // Jika form upah pekerja diisi, catat ke Sdm (bisa lebih dari satu)
        if ($request->has('nama_pekerja') && is_array($request->nama_pekerja)) {
            foreach ($request->nama_pekerja as $index => $nama) {
                $jmlHari = $request->jumlah_hari[$index] ?? null;
                $tarif = $request->tarif_harian[$index] ?? null;

                // Pastikan baris ini diisi dengan lengkap
                if (!empty($nama) && !empty($jmlHari) && !empty($tarif)) {
                    \App\Models\Sdm::create([
                        'id_jadwal' => $request->id_jadwal,
                        'id_komoditas' => $request->id_komoditas,
                        'id_area' => $request->id_area,
                        'nama_pekerja' => $nama,
                        'jenis_pekerjaan' => 'Panen',
                        'jumlah_hari' => $jmlHari,
                        'tarif_harian' => $tarif,
                        'total_upah' => $jmlHari * $tarif,
                        'tanggal_bayar' => $request->tanggal,
                        'keterangan' => 'Upah panen otomatis dari form panen',
                    ]);
                    
                    LogAktivitas::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'Catat Upah Pekerja (via Panen)',
                        'deskripsi' => Auth::user()->name . ' mencatat pembayaran upah pekerja panen a.n. ' . $nama
                    ]);
                }
            }
        }

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Input Transaksi Panen',
            'deskripsi' => Auth::user()->name . ' mencatat hasil panen netto ' . $request->netto_panen . ' Kg'
        ]);

        return redirect()->route('panen.index')
            ->with('sukses', 'Data panen berhasil dicatat.');
    }

    public function edit($id)
    {
        $panen = Panen::findOrFail($id);
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $queryArea = AreaKebun::query();
        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $queryArea->where('id_komoditas', 1);
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $queryArea->where('id_komoditas', 2);
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $queryArea->where('id_komoditas', 3);
            }
        }

        // Filter area yang sudah bisa produktif
        $area = $queryArea->get()->filter(function ($lahan) use ($panen) {
            // Tetap tampilkan lahan yang sudah terpilih sebelumnya
            if ($lahan->id_area == $panen->id_area) return true;
            
            $isProduktif = $lahan->status_area === 'Aktif' || \Carbon\Carbon::parse($lahan->estimasi_panen)->isPast() || \Carbon\Carbon::parse($lahan->estimasi_panen)->isToday();
            if (!$isProduktif) return false;
            
            // Cek cooldown panen
            $lastPanen = \App\Models\Panen::where('id_area', $lahan->id_area)->orderBy('tanggal', 'desc')->first();
            if ($lastPanen) {
                $lastDate = \Carbon\Carbon::parse($lastPanen->tanggal);
                $now = \Carbon\Carbon::now();
                
                if ($lahan->id_komoditas == 1 || $lahan->id_komoditas == 3) {
                    if ($lastDate->copy()->addMonths(6)->isAfter($now)) return false;
                } elseif ($lahan->id_komoditas == 2) {
                    if ($lastDate->copy()->addDays(14)->isAfter($now)) return false;
                }
            }
            
            return true;
        });
        $peralatan = Peralatan::all();
        $perlengkapan = Perlengkapan::all();
        
        return view('panen.edit', compact('panen', 'jadwal', 'komoditas', 'area', 'peralatan', 'perlengkapan'));
    }

    public function update(Request $request, $id)
    {
        $panen = Panen::findOrFail($id);

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_area' => 'required|exists:area_kebun,id_area',
            'id_peralatan' => 'nullable|array',
            'id_peralatan.*' => 'exists:peralatan,id_peralatan',
            'id_perlengkapan' => 'nullable|array',
            'id_perlengkapan.*' => 'exists:perlengkapan,id_perlengkapan',
            'tanggal' => 'required|date',
            'gross_panen' => 'required|numeric|min:0.01',
            'netto_panen' => 'required|numeric|min:0.01|lte:gross_panen',
            'keterangan' => 'nullable|string',
        ], [
            'netto_panen.lte' => 'Netto panen tidak boleh melebihi gross panen.',
        ]);

        // Cek cooldown panen di sisi server (abaikan panen ini sendiri)
        $lahan = AreaKebun::find($request->id_area);
        if ($lahan) {
            $lastPanen = \App\Models\Panen::where('id_area', $lahan->id_area)
                ->where('id_panen', '!=', $id) // abaikan panen yg sedang diedit
                ->orderBy('tanggal', 'desc')->first();
            if ($lastPanen) {
                $lastDate = \Carbon\Carbon::parse($lastPanen->tanggal);
                $requestDate = \Carbon\Carbon::parse($request->tanggal);
                
                if ($lahan->id_komoditas == 1 || $lahan->id_komoditas == 3) {
                    if ($lastDate->copy()->addMonths(6)->isAfter($requestDate)) {
                        return back()->withInput()->with('gagal', 'Lahan ini belum bisa dipanen pada tanggal tersebut. Jeda waktu panen minimal 6 bulan dari panen terakhir ('. $lastDate->format('d M Y') .').');
                    }
                } elseif ($lahan->id_komoditas == 2) {
                    if ($lastDate->copy()->addDays(14)->isAfter($requestDate)) {
                        return back()->withInput()->with('gagal', 'Lahan ini belum bisa dipanen pada tanggal tersebut. Jeda waktu panen minimal 2 minggu dari panen terakhir ('. $lastDate->format('d M Y') .').');
                    }
                }
            }
        }

        $data = $request->all();
        $data['id_peralatan'] = $request->id_peralatan ? implode(',', $request->id_peralatan) : '1';
        $data['id_perlengkapan'] = $request->id_perlengkapan ? implode(',', $request->id_perlengkapan) : '1';

        $panen->update($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Transaksi Panen',
            'deskripsi' => Auth::user()->name . ' mengubah catatan hasil panen menjadi seberat ' . $request->netto_panen . ' Kg.'
        ]);

        return redirect()->route('panen.index')
            ->with('sukses', 'Data panen berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'owner') {
            return redirect()->route('panen.index')->with('gagal', 'Akses ditolak!');
        }

        $request->validate([
            'id_status' => 'required|exists:status,id_status'
        ]);

        $panen = Panen::findOrFail($id);
        $panen->update(['id_status' => $request->id_status]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Status Panen',
            'deskripsi' => Auth::user()->name . ' memperbarui status riwayat suatu panen.'
        ]);

        return redirect()->route('panen.index')->with('sukses', 'Status hasil panen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'owner') {
            return redirect()->route('panen.index')
                ->with('gagal', 'Akses ditolak! Hanya Pemilik yang dapat menghapus data.');
        }

        $panen = Panen::findOrFail($id);
        $panen->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Transaksi Panen',
            'deskripsi' => Auth::user()->name . ' membatalkan dan menghapus riwayat panen seberat ' . $panen->netto_panen . ' Kg.'
        ]);

        return redirect()->route('panen.index')
            ->with('sukses', 'Data panen berhasil dihapus.');
    }
}
