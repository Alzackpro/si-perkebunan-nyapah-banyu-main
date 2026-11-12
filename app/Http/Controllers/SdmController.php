<?php

namespace App\Http\Controllers;

use App\Models\Sdm;
use App\Models\Jadwal;
use App\Models\Komoditas;
use App\Models\AreaKebun;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SdmController extends Controller
{
    public function index(Request $request)
    {
        $query = Sdm::with(['jadwal', 'komoditas', 'area']);

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
            $query->where(function($q) use ($request) {
                $q->where('nama_pekerja', 'like', '%' . $request->cari . '%')
                  ->orWhere('jenis_pekerjaan', 'like', '%' . $request->cari . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->cari . '%');
            });
        }

        $dataSdm = $query->orderBy('tanggal_bayar', 'desc')->paginate(10);
        $totalUpah = $query->sum('total_upah');

        return view('sdm.index', compact('dataSdm', 'totalUpah'));
    }

    public function create()
    {
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $area = AreaKebun::all();
        
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

        return view('sdm.create', compact('jadwal', 'komoditas', 'area', 'defaultKomoditas', 'defaultJadwal', 'defaultArea'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_area' => 'required|exists:area_kebun,id_area',
            'nama_pekerja' => 'required|string|max:100',
            'jenis_pekerjaan' => 'required|string|max:50',
            'jumlah_hari' => 'required|integer|min:1',
            'tarif_harian' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['total_upah'] = $request->jumlah_hari * $request->tarif_harian;

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_upah'), $filename);
            $data['bukti_pembayaran'] = 'uploads/bukti_upah/' . $filename;
        }

        Sdm::create($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Catat Upah Pekerja',
            'deskripsi' => Auth::user()->name . ' mencatat pembayaran upah pekerja a.n. ' . $request->nama_pekerja
        ]);

        return redirect()->route('sdm.index')->with('sukses', 'Data pembayaran upah berhasil dicatat.');
    }

    public function edit($id)
    {
        $sdm = Sdm::findOrFail($id);
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $area = AreaKebun::all();
        
        return view('sdm.edit', compact('sdm', 'jadwal', 'komoditas', 'area'));
    }

    public function update(Request $request, $id)
    {
        $sdm = Sdm::findOrFail($id);

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_area' => 'required|exists:area_kebun,id_area',
            'nama_pekerja' => 'required|string|max:100',
            'jenis_pekerjaan' => 'required|string|max:50',
            'jumlah_hari' => 'required|integer|min:1',
            'tarif_harian' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['total_upah'] = $request->jumlah_hari * $request->tarif_harian;

        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old file if exists
            if ($sdm->bukti_pembayaran && file_exists(public_path($sdm->bukti_pembayaran))) {
                unlink(public_path($sdm->bukti_pembayaran));
            }
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_upah'), $filename);
            $data['bukti_pembayaran'] = 'uploads/bukti_upah/' . $filename;
        }

        $sdm->update($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Data Upah',
            'deskripsi' => Auth::user()->name . ' mengubah catatan upah pegawai senilai Rp ' . number_format($request->total_upah, 0, ',', '.')
        ]);

        return redirect()->route('sdm.index')->with('sukses', 'Data pembayaran upah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'owner') {
            return redirect()->route('sdm.index')->with('gagal', 'Akses ditolak!');
        }

        $sdm = Sdm::findOrFail($id);
        
        if ($sdm->bukti_pembayaran && file_exists(public_path($sdm->bukti_pembayaran))) {
            unlink(public_path($sdm->bukti_pembayaran));
        }

        $sdm->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Data Upah',
            'deskripsi' => Auth::user()->name . ' menghapus suatu riwayat pembayaran upah'
        ]);

        return redirect()->route('sdm.index')->with('sukses', 'Data pembayaran upah berhasil dihapus.');
    }
}
