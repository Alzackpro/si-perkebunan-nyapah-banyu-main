<?php

namespace App\Http\Controllers;

use App\Models\Panen;
use App\Models\Jadwal;
use App\Models\Status;
use App\Models\Komoditas;
use App\Models\Pengiriman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengiriman::with(['panen', 'jadwal', 'komoditas', 'status_pengiriman']);

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
            $query->where('keterangan', 'like', '%' . $request->cari . '%');
        }

        $dataPengiriman = $query->orderBy('tanggal_pengiriman', 'desc')->paginate(10);
        $totalPenjualan = $query->sum('total_penjualan');
        
        return view('pengiriman.index', compact('dataPengiriman', 'totalPenjualan'));
    }

    public function create()
    {
        // Hanya bisa memilih panen yang statusnya Terjual (id_status = 3) atau disetujui owner
        // DAN belum ada di tabel pengiriman
        $idPanenDikirim = Pengiriman::whereNotNull('id_panen')->pluck('id_panen')->toArray();

        $queryPanen = Panen::where('id_status', 3)
            ->when(!empty($idPanenDikirim), function ($q) use ($idPanenDikirim) {
                return $q->whereNotIn('id_panen', $idPanenDikirim);
            })
            ->orderBy('tanggal', 'desc');
        
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $statusList = Status::all();
        
        $defaultKomoditas = null;
        $defaultJadwal = null;

        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $defaultKomoditas = 1; $defaultJadwal = 10;
                $queryPanen->where('id_komoditas', 1);
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $defaultKomoditas = 2; $defaultJadwal = 11;
                $queryPanen->where('id_komoditas', 2);
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $defaultKomoditas = 3; $defaultJadwal = 12;
                $queryPanen->where('id_komoditas', 3);
            }
        }
        
        $dataPanen = $queryPanen->get();

        return view('pengiriman.create', compact('dataPanen', 'jadwal', 'komoditas', 'statusList', 'defaultKomoditas', 'defaultJadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_panen' => 'required|exists:panen,id_panen',
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_status' => 'required|exists:status,id_status',
            'tanggal_pengiriman' => 'required|date',
            'jumlah_dikirim' => 'required|numeric|min:0.01',
            'harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['total_penjualan'] = $request->jumlah_dikirim * $request->harga_jual;

        Pengiriman::create($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Catat Pengiriman/Penjualan',
            'deskripsi' => Auth::user()->name . ' mencatat pengiriman & penjualan hasil panen seberat ' . $request->jumlah_dikirim . ' Kg.'
        ]);

        return redirect()->route('pengiriman.index')->with('sukses', 'Data pengiriman penjualan berhasil dicatat.');
    }

    public function edit($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);
        
        // Memasukkan panen dari id_panen saat ini, ditambah panen lain yang terjual dan belum dikirim
        $idPanenDikirim = Pengiriman::whereNotNull('id_panen')
            ->where('id_panen', '!=', $pengiriman->id_panen)
            ->pluck('id_panen')
            ->toArray();

        $queryPanen = Panen::where(function($q) use ($idPanenDikirim) {
                $q->where('id_status', 3);
                if (!empty($idPanenDikirim)) {
                    $q->whereNotIn('id_panen', $idPanenDikirim);
                }
            })
            ->orWhere('id_panen', $pengiriman->id_panen)
            ->orderBy('tanggal', 'desc');
        
        if (Auth::user()->isPengurus()) {
            if (str_contains(Auth::user()->email, 'kopi')) {
                $queryPanen->where('id_komoditas', 1);
            } elseif (str_contains(Auth::user()->email, 'sawit')) {
                $queryPanen->where('id_komoditas', 2);
            } elseif (str_contains(Auth::user()->email, 'lada')) {
                $queryPanen->where('id_komoditas', 3);
            }
        }
        
        $dataPanen = $queryPanen->get();
        $jadwal = Jadwal::all();
        $komoditas = Komoditas::all();
        $statusList = Status::all();
        
        return view('pengiriman.edit', compact('pengiriman', 'dataPanen', 'jadwal', 'komoditas', 'statusList'));
    }

    public function update(Request $request, $id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $request->validate([
            'id_panen' => 'required|exists:panen,id_panen',
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'id_komoditas' => 'required|exists:komoditas,id_komoditas',
            'id_status' => 'required|exists:status,id_status',
            'tanggal_pengiriman' => 'required|date',
            'jumlah_dikirim' => 'required|numeric|min:0.01',
            'harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['total_penjualan'] = $request->jumlah_dikirim * $request->harga_jual;

        $pengiriman->update($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Ubah Data Pengiriman',
            'deskripsi' => Auth::user()->name . ' mengubah data pengiriman penjualan seberat ' . $request->jumlah_dikirim . ' Kg.'
        ]);

        return redirect()->route('pengiriman.index')->with('sukses', 'Data pengiriman berhasil diperbarui.');
    }


    public function destroy($id)
    {
        if (Auth::user()->role !== 'owner') {
            return redirect()->route('pengiriman.index')->with('gagal', 'Akses ditolak!');
        }

        $pengiriman = Pengiriman::findOrFail($id);
        $pengiriman->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Data Pengiriman',
            'deskripsi' => Auth::user()->name . ' membatalkan dan menghapus data pengiriman seberat ' . $pengiriman->jumlah_dikirim . ' Kg.'
        ]);

        return redirect()->route('pengiriman.index')->with('sukses', 'Data pengiriman berhasil dihapus.');
    }
}
