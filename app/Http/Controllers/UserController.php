<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan data user (Index)
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('cari') && $request->cari != '') {
            $query->where('username', 'like', '%' . $request->cari . '%')
                  ->orWhere('email', 'like', '%' . $request->cari . '%')
                  ->orWhere('role', 'like', '%' . $request->cari . '%');
        }

        $dataUser = $query->orderBy('username', 'asc')->paginate(10);

        return view('user.index', compact('dataUser'));
    }

    /**
     * Tampilkan form tambah user (Create)
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Simpan data user baru (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:20|unique:users,username',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:owner,pengurus',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'role.required' => 'Hak akses (Role) wajib dipilih.',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Master User',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' mendaftarkan akun baru: ' . $user->username
        ]);

        return redirect()->route('user.index')
            ->with('sukses', 'Akun pengguna berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit user (Edit)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Perbarui data user (Update)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'username' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'role' => 'required|in:owner,pengurus',
        ];

        // Jika password diisi, maka divalidasi. Jika tidak, abaikan.
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Master User',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' mengubah rincian akun: ' . $user->username
        ]);

        return redirect()->route('user.index')
            ->with('sukses', 'Akun pengguna berhasil diperbarui.');
    }

    /**
     * Hapus data user (Destroy)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteksi agar pengguna tidak bisa menghapus akunnya sendiri
        if ($user->id_user == Auth::id()) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $username = $user->username;
        $user->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Master User',
            'deskripsi' => 'Owner ' . Auth::user()->name . ' menghapus akun: ' . $username
        ]);

        return redirect()->route('user.index')
            ->with('sukses', 'Akun pengguna berhasil dihapus.');
    }
}
