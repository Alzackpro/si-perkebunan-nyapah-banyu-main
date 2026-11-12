<?php

namespace App\Http\Controllers;

use App\Models\Perlengkapan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerlengkapanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perlengkapan::query();

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_perlengkapan', 'like', '%' . $request->cari . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->cari . '%');
        }

        $dataPerlengkapan = $query->orderBy('nama_perlengkapan', 'asc')->paginate(10);
        return view('perlengkapan.index', compact('dataPerlengkapan'));
    }

    public function create()
    {
        return view('perlengkapan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perlengkapan' => 'required|string|max:100|unique:perlengkapan,nama_perlengkapan',
            'keterangan' => 'nullable|string',
        ], [
            'nama_perlengkapan.required' => 'Nama perlengkapan wajib diisi.',
            'nama_perlengkapan.unique' => 'Nama perlengkapan ini sudah ada di sistem.',
        ]);

        $perlengkapan = Perlengkapan::create($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Master Perlengkapan',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' menambahkan perlengkapan baru: ' . $perlengkapan->nama_perlengkapan
        ]);

        return redirect()->route('perlengkapan.index')->with('sukses', 'Data perlengkapan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $perlengkapan = Perlengkapan::findOrFail($id);
        return view('perlengkapan.edit', compact('perlengkapan'));
    }

    public function update(Request $request, $id)
    {
        $perlengkapan = Perlengkapan::findOrFail($id);

        $request->validate([
            'nama_perlengkapan' => 'required|string|max:100|unique:perlengkapan,nama_perlengkapan,' . $perlengkapan->id_perlengkapan . ',id_perlengkapan',
            'keterangan' => 'nullable|string',
        ], [
            'nama_perlengkapan.required' => 'Nama perlengkapan wajib diisi.',
            'nama_perlengkapan.unique' => 'Nama perlengkapan ini sudah ada di sistem.',
        ]);

        $perlengkapan->update($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Master Perlengkapan',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' mengubah rincian perlengkapan: ' . $perlengkapan->nama_perlengkapan
        ]);

        return redirect()->route('perlengkapan.index')->with('sukses', 'Data perlengkapan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $perlengkapan = Perlengkapan::findOrFail($id);
        $namaPerlengkapan = $perlengkapan->nama_perlengkapan;
        
        try {
            $perlengkapan->delete();
            
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Master Perlengkapan',
                'deskripsi' => 'Owner ' . Auth::user()->name . ' menghapus perlengkapan: ' . $namaPerlengkapan
            ]);

            return redirect()->route('perlengkapan.index')->with('sukses', 'Data perlengkapan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('perlengkapan.index')->with('error', 'Perlengkapan ini tidak bisa dihapus karena sedang digunakan dalam operasional.');
        }
    }
}
