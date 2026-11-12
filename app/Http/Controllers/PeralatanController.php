<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeralatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peralatan::query();

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_peralatan', 'like', '%' . $request->cari . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->cari . '%');
        }

        $dataPeralatan = $query->orderBy('nama_peralatan', 'asc')->paginate(10);
        return view('peralatan.index', compact('dataPeralatan'));
    }

    public function create()
    {
        return view('peralatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peralatan' => 'required|string|max:100|unique:peralatan,nama_peralatan',
            'keterangan' => 'nullable|string',
        ], [
            'nama_peralatan.required' => 'Nama peralatan wajib diisi.',
            'nama_peralatan.unique' => 'Nama peralatan ini sudah ada di sistem.',
        ]);

        $peralatan = Peralatan::create($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Master Peralatan',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' menambahkan peralatan baru: ' . $peralatan->nama_peralatan
        ]);

        return redirect()->route('peralatan.index')->with('sukses', 'Data peralatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        return view('peralatan.edit', compact('peralatan'));
    }

    public function update(Request $request, $id)
    {
        $peralatan = Peralatan::findOrFail($id);

        $request->validate([
            'nama_peralatan' => 'required|string|max:100|unique:peralatan,nama_peralatan,' . $peralatan->id_peralatan . ',id_peralatan',
            'keterangan' => 'nullable|string',
        ], [
            'nama_peralatan.required' => 'Nama peralatan wajib diisi.',
            'nama_peralatan.unique' => 'Nama peralatan ini sudah ada di sistem.',
        ]);

        $peralatan->update($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Master Peralatan',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' mengubah rincian peralatan: ' . $peralatan->nama_peralatan
        ]);

        return redirect()->route('peralatan.index')->with('sukses', 'Data peralatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        $namaPeralatan = $peralatan->nama_peralatan;
        
        try {
            $peralatan->delete();
            
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Master Peralatan',
                'deskripsi' => 'Owner ' . Auth::user()->name . ' menghapus peralatan: ' . $namaPeralatan
            ]);

            return redirect()->route('peralatan.index')->with('sukses', 'Data peralatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('peralatan.index')->with('error', 'Peralatan ini tidak bisa dihapus karena sedang digunakan dalam operasional.');
        }
    }
}
