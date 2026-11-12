<?php

namespace App\Http\Controllers;

use App\Models\Operasional;
use App\Models\Jadwal;
use App\Models\Komoditas;
use App\Models\Pegawai;
use App\Models\Peralatan;
use App\Models\Perlengkapan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperasionalController extends Controller
{
    public function index(Request $request)
    {
        $query = Operasional::with(['jadwal', 'komoditas', 'pegawai', 'peralatan', 'perlengkapan']);

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

        if ($request->has('jenis_perawatan') && $request->jenis_perawatan != '') {
            $query->where('jenis_perawatan', $request->jenis_perawatan);
        }

        $dataOperasional = $query->orderBy('tanggal_perawatan', 'desc')->paginate(10);

        return view('operasional.index', compact('dataOperasional'));
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
        $defaultArea = null;

        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $defaultKomoditas = 1; $defaultPegawai = 3; $defaultJadwal = 4; $defaultArea = 1;
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $defaultKomoditas = 2; $defaultPegawai = 2; $defaultJadwal = 5; $defaultArea = 2;
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $defaultKomoditas = 3; $defaultPegawai = 4; $defaultJadwal = 6; $defaultArea = 3;
            }
        }

        return view('operasional.create', compact('jadwal', 'komoditas', 'pegawai', 'peralatan', 'perlengkapan', 'defaultKomoditas', 'defaultPegawai', 'defaultJadwal', 'defaultArea'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_area' => 'required|exists:area_kebun,id_area',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_peralatan' => 'nullable|array',
            'id_peralatan.*' => 'exists:peralatan,id_peralatan',
            'id_perlengkapan' => 'nullable|array',
            'id_perlengkapan.*' => 'exists:perlengkapan,id_perlengkapan',
            'tanggal_perawatan' => 'required|date',
            'jenis_perawatan' => 'required|in:Pemupukan,Obat Hama,Pemupukan dan Obat Hama',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        // Boleh dikosongkan, fallback ke 1 (Tidak Ada)
        $data['id_peralatan'] = $request->id_peralatan ? json_encode($request->id_peralatan) : json_encode([1]);
        $data['id_perlengkapan'] = $request->id_perlengkapan ? json_encode($request->id_perlengkapan) : json_encode([1]);

        $operasional = Operasional::create($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Input Perawatan Lahan',
            'deskripsi' => Auth::user()->name . ' mencatat aktivitas perawatan: ' . $request->jenis_perawatan
        ]);

        return redirect()->route('operasional.index')
            ->with('sukses', 'Data perawatan berhasil dicatat.');
    }

    public function edit($id)
    {
        $operasional = Operasional::findOrFail($id);
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $pegawai = Pegawai::all();
        $peralatan = Peralatan::all();
        $perlengkapan = Perlengkapan::all();
        
        return view('operasional.edit', compact('operasional', 'jadwal', 'komoditas', 'pegawai', 'peralatan', 'perlengkapan'));
    }

    public function update(Request $request, $id)
    {
        $operasional = Operasional::findOrFail($id);

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_area' => 'required|exists:area_kebun,id_area',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'id_peralatan' => 'nullable|array',
            'id_peralatan.*' => 'exists:peralatan,id_peralatan',
            'id_perlengkapan' => 'nullable|array',
            'id_perlengkapan.*' => 'exists:perlengkapan,id_perlengkapan',
            'tanggal_perawatan' => 'required|date',
            'jenis_perawatan' => 'required|in:Pemupukan,Obat Hama,Pemupukan dan Obat Hama',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['id_peralatan'] = $request->id_peralatan ? json_encode($request->id_peralatan) : json_encode([1]);
        $data['id_perlengkapan'] = $request->id_perlengkapan ? json_encode($request->id_perlengkapan) : json_encode([1]);

        $operasional->update($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Perawatan Lahan',
            'deskripsi' => Auth::user()->name . ' mengubah riwayat perawatan untuk kegiatan ' . $request->jenis_perawatan
        ]);

        return redirect()->route('operasional.index')
            ->with('sukses', 'Data perawatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $operasional = Operasional::findOrFail($id);
        $operasional->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Perawatan Lahan',
            'deskripsi' => Auth::user()->name . ' menghapus suatu riwayat perawatan'
        ]);

        return redirect()->route('operasional.index')
            ->with('sukses', 'Data perawatan berhasil dihapus.');
    }
}
