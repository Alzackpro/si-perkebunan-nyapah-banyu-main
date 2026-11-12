<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman pengaturan profile
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }

    /**
     * Update data profile dan password pengguna
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id_user . ',id_user'],
        ];

        // Jika password diisi, lakukan validasi ubah password
        if ($request->filled('current_password') || $request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh pengguna lain.',
            'current_password.required' => 'Password saat ini wajib diisi untuk mengganti password.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Simpan nama dan email
        $user->username = $validated['name'];
        $user->email = $validated['email'];

        // Update password jika disediakan
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => $user->id_user,
            'aktivitas' => 'Update Pengaturan Profil',
            'deskripsi' => 'Pengguna ' . $user->name . ' (' . $user->role . ') memperbarui pengaturan profil atau kata sandi.'
        ]);

        return redirect()->route('settings')->with('sukses', 'Pengaturan profil berhasil diperbarui.');
    }
}
