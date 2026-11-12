<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $query = Status::query();

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_status', 'like', '%' . $request->cari . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->cari . '%');
        }

        $dataStatus = $query->orderBy('nama_status', 'asc')->paginate(10);
        return view('status.index', compact('dataStatus'));
    }

    public function create()
    {
        return view('status.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_status' => 'required|string|max:100|unique:status,nama_status',
            'keterangan' => 'nullable|string',
        ], [
            'nama_status.required' => 'Nama status wajib diisi.',
            'nama_status.unique' => 'Nama status ini sudah ada di sistem.',
        ]);

        $status = Status::create($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Master Status',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' menambahkan status baru: ' . $status->nama_status
        ]);

        return redirect()->route('status.index')->with('sukses', 'Data status berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $status = Status::findOrFail($id);
        return view('status.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $status = Status::findOrFail($id);

        $request->validate([
            'nama_status' => 'required|string|max:100|unique:status,nama_status,' . $status->id_status . ',id_status',
            'keterangan' => 'nullable|string',
        ], [
            'nama_status.required' => 'Nama status wajib diisi.',
            'nama_status.unique' => 'Nama status ini sudah ada di sistem.',
        ]);

        $status->update($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Master Status',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' mengubah rincian status: ' . $status->nama_status
        ]);

        return redirect()->route('status.index')->with('sukses', 'Data status berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $status = Status::findOrFail($id);
        $namaStatus = $status->nama_status;
        
        try {
            $status->delete();
            
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Master Status',
                'deskripsi' => 'Owner ' . Auth::user()->name . ' menghapus status: ' . $namaStatus
            ]);

            return redirect()->route('status.index')->with('sukses', 'Data status berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('status.index')->with('error', 'Status ini tidak bisa dihapus karena sedang digunakan dalam data lain.');
        }
    }
}
