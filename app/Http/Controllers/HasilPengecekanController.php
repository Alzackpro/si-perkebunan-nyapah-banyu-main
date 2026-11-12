<?php

namespace App\Http\Controllers;

use App\Models\HasilPengecekan;
use App\Models\AreaKebun;
use App\Models\Peralatan;
use App\Models\Perlengkapan;
use App\Models\Komoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilPengecekanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = HasilPengecekan::with(['areaKebun', 'pengguna', 'peralatan', 'perlengkapan', 'komoditasBibit'])
            ->orderBy('tanggal', 'desc');
            
        // Jika pengurus, filter data sesuai dengan area yang bisa mereka kelola
        if ($user->isPengurus()) {
            $idKomoditas = 0;
            if (str_contains($user->email, 'kopi')) $idKomoditas = 1;
            elseif (str_contains($user->email, 'sawit')) $idKomoditas = 2;
            elseif (str_contains($user->email, 'lada')) $idKomoditas = 3;

            $query->whereHas('areaKebun', function($q) use ($idKomoditas) {
                if ($idKomoditas > 0) {
                    $q->where('id_komoditas', $idKomoditas);
                }
            });
        }
        
        $hasilPengecekan = $query->paginate(15);
        return view('hasil_pengecekan.index', compact('hasilPengecekan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        $queryArea = AreaKebun::query();
        if ($user->isPengurus()) {
            $idKomoditas = 0;
            if (str_contains($user->email, 'kopi')) $idKomoditas = 1;
            elseif (str_contains($user->email, 'sawit')) $idKomoditas = 2;
            elseif (str_contains($user->email, 'lada')) $idKomoditas = 3;

            if ($idKomoditas > 0) {
                $queryArea->where('id_komoditas', $idKomoditas);
            }
        }
        $areaKebun = $queryArea->get();

        $peralatan = Peralatan::all();
        $perlengkapan = Perlengkapan::all();
        $komoditas = Komoditas::all();

        return view('hasil_pengecekan.create', compact('areaKebun', 'peralatan', 'perlengkapan', 'komoditas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_area' => 'required|exists:area_kebun,id_area',
            'aktivitas' => 'required|string',
            'id_peralatan' => 'nullable|exists:peralatan,id_peralatan',
            'id_perlengkapan' => 'nullable|exists:perlengkapan,id_perlengkapan',
            'id_komoditas_bibit' => 'nullable|exists:komoditas,id_komoditas',
            'jumlah_bibit' => 'nullable|numeric|min:0'
        ]);

        HasilPengecekan::create([
            'tanggal' => $request->tanggal,
            'id_area' => $request->id_area,
            'id_pengguna' => Auth::id(),
            'aktivitas' => $request->aktivitas,
            'id_peralatan' => $request->id_peralatan,
            'id_perlengkapan' => $request->id_perlengkapan,
            'id_komoditas_bibit' => $request->id_komoditas_bibit,
            'jumlah_bibit' => $request->jumlah_bibit ?? 0
        ]);

        // Logika FIFO pengurangan stok bibit di Master Pembibitan
        if ($request->id_komoditas_bibit && $request->jumlah_bibit > 0) {
            $jumlahDibutuhkan = $request->jumlah_bibit;
            $dataBibitTersedia = \App\Models\Pembibitan::where('id_komoditas', $request->id_komoditas_bibit)
                                ->where('jumlah_bibit', '>', 0)
                                ->orderBy('tanggal_pembibitan', 'asc')
                                ->get();
                                
            foreach ($dataBibitTersedia as $bibit) {
                if ($jumlahDibutuhkan <= 0) break;
                
                if ($bibit->jumlah_bibit >= $jumlahDibutuhkan) {
                    $bibit->jumlah_bibit -= $jumlahDibutuhkan;
                    $bibit->save();
                    $jumlahDibutuhkan = 0;
                } else {
                    $jumlahDibutuhkan -= $bibit->jumlah_bibit;
                    $bibit->jumlah_bibit = 0;
                    $bibit->save();
                }
            }
        }

        return redirect()->route('hasil-pengecekan.index')->with('success', 'Hasil Pengecekan berhasil disimpan!');
    }
}
