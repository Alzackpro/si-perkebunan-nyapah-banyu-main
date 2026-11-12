<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Komoditas;
use App\Models\AreaKebun;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['komoditas', 'area']);

        if ($request->has('cari') && $request->cari != '') {
            $query->where('jenis_kegiatan', 'like', '%' . $request->cari . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->cari . '%')
                  ->orWhereHas('komoditas', function($q) use ($request) {
                      $q->where('nama_komoditas', 'like', '%' . $request->cari . '%');
                  })
                  ->orWhereHas('area', function($q) use ($request) {
                      $q->where('nama_area', 'like', '%' . $request->cari . '%');
                  });
        }

        $dataJadwal = $query->orderBy('jenis_kegiatan', 'asc')->paginate(10);
        return view('jadwal.index', compact('dataJadwal'));
    }

    public function create()
    {
        $komoditas = Komoditas::all();
        $areas = AreaKebun::all();
        return view('jadwal.create', compact('komoditas', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_area' => 'required|exists:area_kebun,id_area',
            'jenis_kegiatan' => 'required|in:panen,perawatan,pengiriman,pembibitan',
            'frekuensi' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ], [
            'id_komoditas.required' => 'Komoditas wajib dipilih.',
            'id_area.required' => 'Area kebun wajib dipilih.',
            'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
            'frekuensi.required' => 'Frekuensi wajib diisi angka.',
        ]);

        $jadwal = Jadwal::create($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Master Jadwal',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' menambahkan jadwal ' . $jadwal->jenis_kegiatan
        ]);

        return redirect()->route('jadwal.index')->with('sukses', 'Data jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $komoditas = Komoditas::all();
        $areas = AreaKebun::all();
        return view('jadwal.edit', compact('jadwal', 'komoditas', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_area' => 'required|exists:area_kebun,id_area',
            'jenis_kegiatan' => 'required|in:panen,perawatan,pengiriman,pembibitan',
            'frekuensi' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Master Jadwal',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' mengubah rincian jadwal ' . $jadwal->jenis_kegiatan
        ]);

        return redirect()->route('jadwal.index')->with('sukses', 'Data jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kegiatan = $jadwal->jenis_kegiatan;
        
        try {
            $jadwal->delete();
            
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Master Jadwal',
                'deskripsi' => 'Owner ' . Auth::user()->name . ' menghapus jadwal ' . $kegiatan
            ]);

            return redirect()->route('jadwal.index')->with('sukses', 'Data jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('jadwal.index')->with('error', 'Jadwal ini tidak bisa dihapus karena sedang terkait dengan data operasional lain.');
        }
    }
}
