<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomoditasController extends Controller
{
    /**
     * Tampilkan data komoditas (Index)
     */
    public function index(Request $request)
    {
        $query = Komoditas::query();

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_komoditas', 'like', '%' . $request->cari . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->cari . '%');
        }

        $dataKomoditas = $query->orderBy('nama_komoditas', 'asc')->paginate(10);

        return view('komoditas.index', compact('dataKomoditas'));
    }

    /**
     * Tampilkan form tambah komoditas (Create)
     */
    public function create()
    {
        return view('komoditas.create');
    }

    /**
     * Simpan data komoditas baru (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_komoditas' => 'required|string|max:100|unique:komoditas,nama_komoditas',
            'keterangan' => 'nullable|string',
        ], [
            'nama_komoditas.required' => 'Nama komoditas wajib diisi.',
            'nama_komoditas.unique' => 'Nama komoditas ini sudah ada di sistem.',
        ]);

        $komoditas = Komoditas::create($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Master Komoditas',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' menambahkan jenis tanaman baru: ' . $komoditas->nama_komoditas
        ]);

        return redirect()->route('komoditas.index')
            ->with('sukses', 'Data komoditas berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit komoditas (Edit)
     */
    public function edit($id)
    {
        $komoditas = Komoditas::findOrFail($id);
        return view('komoditas.edit', compact('komoditas'));
    }

    /**
     * Perbarui data komoditas (Update)
     */
    public function update(Request $request, $id)
    {
        $komoditas = Komoditas::findOrFail($id);

        $request->validate([
            'nama_komoditas' => 'required|string|max:100|unique:komoditas,nama_komoditas,' . $komoditas->id_komoditas . ',id_komoditas',
            'keterangan' => 'nullable|string',
        ], [
            'nama_komoditas.required' => 'Nama komoditas wajib diisi.',
            'nama_komoditas.unique' => 'Nama komoditas ini sudah ada di sistem.',
        ]);

        $komoditas->update($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Master Komoditas',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' mengubah rincian komoditas: ' . $komoditas->nama_komoditas
        ]);

        return redirect()->route('komoditas.index')
            ->with('sukses', 'Data komoditas berhasil diperbarui.');
    }

    /**
     * Hapus data komoditas (Destroy)
     */
    public function destroy($id)
    {
        $komoditas = Komoditas::findOrFail($id);
        $namaKomoditas = $komoditas->nama_komoditas;
        
        try {
            $komoditas->delete();
            
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Master Komoditas',
                'deskripsi' => 'Owner ' . Auth::user()->name . ' menghapus komoditas: ' . $namaKomoditas
            ]);

            return redirect()->route('komoditas.index')
                ->with('sukses', 'Data komoditas berhasil dihapus.');
        } catch (\Exception $e) {
            // Bisa terjadi error kalau komoditas ini sedang dipakai di tabel Area Kebun/Pembibitan
            return redirect()->route('komoditas.index')
                ->with('error', 'Komoditas ini tidak bisa dihapus karena sedang digunakan dalam data lahan/pembibitan.');
        }
    }
}
