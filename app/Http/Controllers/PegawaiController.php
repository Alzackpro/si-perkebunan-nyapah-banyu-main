<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    /**
     * Tampilkan data pegawai (Index)
     */
    public function index(Request $request)
    {
        $query = Pegawai::with('user');

        if ($request->has('cari') && $request->cari != '') {
            $query->where('nama_pegawai', 'like', '%' . $request->cari . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->cari . '%')
                  ->orWhere('no_tlp', 'like', '%' . $request->cari . '%');
        }

        $dataPegawai = $query->orderBy('nama_pegawai', 'asc')->paginate(10);

        return view('pegawai.index', compact('dataPegawai'));
    }

    /**
     * Tampilkan form tambah pegawai (Create)
     */
    public function create()
    {
        // Ambil semua user untuk dipilih saat membuat pegawai baru
        $users = User::orderBy('username', 'asc')->get();
        return view('pegawai.create', compact('users'));
    }

    /**
     * Simpan data pegawai baru (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'nama_pegawai' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'no_tlp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'id_user.required' => 'Akun pengguna wajib dipilih.',
            'nama_pegawai.required' => 'Nama pegawai wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $pegawai = Pegawai::create($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Master Pegawai',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' menambahkan data pegawai baru: ' . $pegawai->nama_pegawai
        ]);

        return redirect()->route('pegawai.index')
            ->with('sukses', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit pegawai (Edit)
     */
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $users = User::orderBy('username', 'asc')->get();
        
        return view('pegawai.edit', compact('pegawai', 'users'));
    }

    /**
     * Perbarui data pegawai (Update)
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'nama_pegawai' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'no_tlp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $pegawai->update($request->all());

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Master Pegawai',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' mengubah data pegawai: ' . $pegawai->nama_pegawai
        ]);

        return redirect()->route('pegawai.index')
            ->with('sukses', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Hapus data pegawai (Destroy)
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $namaPegawai = $pegawai->nama_pegawai;
        $pegawai->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Master Pegawai',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' menghapus data pegawai: ' . $namaPegawai
        ]);

        return redirect()->route('pegawai.index')
            ->with('sukses', 'Data pegawai berhasil dihapus.');
    }
}
