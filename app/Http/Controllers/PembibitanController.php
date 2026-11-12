<?php

namespace App\Http\Controllers;

use App\Models\Pembibitan;
use App\Models\Jadwal;
use App\Models\Komoditas;
use App\Models\Pegawai;
use App\Models\Peralatan;
use App\Models\Perlengkapan;
use App\Models\Tanam;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembibitanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembibitan::with(['jadwal', 'komoditas', 'pegawai', 'peralatan', 'perlengkapan']);

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
            $query->where('keterangan', 'like', '%' . $request->cari . '%')
                  ->orWhereHas('pegawai', function($q) use ($request) {
                      $q->where('nama_pegawai', 'like', '%' . $request->cari . '%');
                  });
        }

        // Calculate Totals for the Cards
        $sumQuery = clone $query;
        $totalBibitMasuk = $sumQuery->sum('jumlah_bibit');
        
        // Find how many planted/penyulaman based on role
        $queryTanam = Tanam::query();
        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $queryTanam->whereHas('area', function ($q) { $q->where('id_komoditas', 1); });
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $queryTanam->whereHas('area', function ($q) { $q->where('id_komoditas', 2); });
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $queryTanam->whereHas('area', function ($q) { $q->where('id_komoditas', 3); });
            }
        }
        $firstPembibitanDate = (clone $sumQuery)->min('tanggal_pembibitan');
        if ($firstPembibitanDate) {
            $queryTanam->where('tanggal_tanam', '>=', $firstPembibitanDate);
        }

        $totalTanam = $queryTanam->sum('jumlah_tanam');
        $sisaStok = max(0, $totalBibitMasuk - $totalTanam);

        // Calculate Siap Tanam & Belum Siap
        $bibitSiapTanam = 0;
        $bibitBelumSiap = 0;

        foreach ($sumQuery->get() as $b) {
            $targetBulan = ($b->id_komoditas == 2) ? 12 : 6;
            
            $usiaAwal = (int) $b->usia_bibit;
            $bulanBerlalu = \Carbon\Carbon::parse($b->tanggal_pembibitan)->diffInMonths(\Carbon\Carbon::now());
            $usiaSekarang = (int) floor($usiaAwal + $bulanBerlalu);

            if ($usiaSekarang >= $targetBulan) {
                $bibitSiapTanam += $b->jumlah_bibit;
            } else {
                $bibitBelumSiap += $b->jumlah_bibit;
            }
        }

        if ($totalTanam > $bibitSiapTanam) {
            $sisaTanam = $totalTanam - $bibitSiapTanam;
            $bibitSiapTanam = 0;
            $bibitBelumSiap = max(0, $bibitBelumSiap - $sisaTanam);
        } else {
            $bibitSiapTanam -= $totalTanam;
        }

        $dataBibit = $query->orderBy('tanggal_pembibitan', 'desc')->paginate(10);
        $komoditas = Komoditas::all();
        $totalAktivitas = $sumQuery->count();

        return view('pembibitan.index', compact(
            'dataBibit', 'komoditas', 'sisaStok', 'totalBibitMasuk', 
            'totalAktivitas', 'bibitSiapTanam', 'bibitBelumSiap'
        ));
    }

    public function create()
    {
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $pegawai = Pegawai::all();
        $peralatan = Peralatan::all();
        $perlengkapan = Perlengkapan::all();
        
        // Auto-fill values based on Pengurus login
        $defaultKomoditas = null;
        $defaultPegawai = null;
        $defaultJadwal = null;

        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $defaultKomoditas = 1; $defaultPegawai = 3; $defaultJadwal = 7;
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $defaultKomoditas = 2; $defaultPegawai = 2; $defaultJadwal = 8;
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $defaultKomoditas = 3; $defaultPegawai = 4; $defaultJadwal = 9;
            }
        }

        return view('pembibitan.create', compact('jadwal', 'komoditas', 'pegawai', 'peralatan', 'perlengkapan', 'defaultKomoditas', 'defaultPegawai', 'defaultJadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_peralatan' => 'nullable|array',
            'id_peralatan.*' => 'exists:peralatan,id_peralatan',
            'id_perlengkapan' => 'nullable|array',
            'id_perlengkapan.*' => 'exists:perlengkapan,id_perlengkapan',
            'tanggal_pembibitan' => 'required|date',
            'usia_bibit' => 'required|integer|min:0',
            'jumlah_bibit' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->except(['jumlah_bibit']);
        $data['id_peralatan'] = $request->id_peralatan ? implode(',', $request->id_peralatan) : '1';
        $data['id_perlengkapan'] = $request->id_perlengkapan ? implode(',', $request->id_perlengkapan) : '1';
        $data['jumlah_bibit'] = $request->jumlah_bibit;
        $data['jumlah_penyulaman'] = 0; // Legacy field
        $pesanLog = 'menambahkan stok bibit baru sebanyak ' . $request->jumlah_bibit;

        $bibit = Pembibitan::create($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Aktivitas Pembibitan',
            'deskripsi' => Auth::user()->name . ' ' . $pesanLog
        ]);

        return redirect()->route('pembibitan.index')
            ->with('sukses', 'Aktivitas berhasil dicatat.');
    }

    public function edit($id)
    {
        $bibit = Pembibitan::findOrFail($id);
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $pegawai = Pegawai::all();
        $peralatan = Peralatan::all();
        $perlengkapan = Perlengkapan::all();
        
        return view('pembibitan.edit', compact('bibit', 'jadwal', 'komoditas', 'pegawai', 'peralatan', 'perlengkapan'));
    }

    public function update(Request $request, $id)
    {
        $bibit = Pembibitan::findOrFail($id);

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_peralatan' => 'nullable|array',
            'id_peralatan.*' => 'exists:peralatan,id_peralatan',
            'id_perlengkapan' => 'nullable|array',
            'id_perlengkapan.*' => 'exists:perlengkapan,id_perlengkapan',
            'tanggal_pembibitan' => 'required|date',
            'usia_bibit' => 'required|integer|min:0',
            'jumlah_bibit' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['id_peralatan'] = $request->id_peralatan ? implode(',', $request->id_peralatan) : '1';
        $data['id_perlengkapan'] = $request->id_perlengkapan ? implode(',', $request->id_perlengkapan) : '1';

        $bibit->update($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Data Pembibitan',
            'deskripsi' => Auth::user()->name . ' mengubah rincian aktivitas pembibitan sebanyak ' . $request->jumlah_bibit . ' bibit'
        ]);

        return redirect()->route('pembibitan.index')
            ->with('sukses', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bibit = Pembibitan::findOrFail($id);
        $bibit->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Aktivitas Pembibitan',
            'deskripsi' => Auth::user()->name . ' menghapus suatu riwayat pembibitan'
        ]);

        return redirect()->route('pembibitan.index')
            ->with('sukses', 'Data aktivitas berhasil dihapus.');
    }
}
