<?php

namespace App\Http\Controllers;

use App\Models\Tanam;
use App\Models\AreaKebun;
use App\Models\Peralatan;
use App\Models\Perlengkapan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TanamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Tanam::with(['area.komoditas', 'area']);

        // Filter berdasarkan komoditas pegawai
        if ($user->isPengurus()) {
            if (str_contains($user->email, 'kopi')) {
                $query->whereHas('area', function ($q) {
                    $q->where('id_komoditas', 1);
                });
            } elseif (str_contains($user->email, 'sawit')) {
                $query->whereHas('area', function ($q) {
                    $q->where('id_komoditas', 2);
                });
            } elseif (str_contains($user->email, 'lada')) {
                $query->whereHas('area', function ($q) {
                    $q->where('id_komoditas', 3);
                });
            }
        }

        if ($request->has('cari') && $request->cari != '') {
            $cari = $request->cari;
            $query->whereHas('area', function ($q) use ($cari) {
                $q->where('nama_lahan', 'like', "%{$cari}%")
                  ->orWhere('nama_area', 'like', "%{$cari}%");
            });
        }

        $dataTanam = $query->orderBy('tanggal_tanam', 'desc')->paginate(10);
        $totalTanamGlobal = $query->sum('jumlah_tanam') ?: 0;
        $totalAktivitas = $query->count();

        return view('tanam.index', compact('dataTanam', 'totalTanamGlobal', 'totalAktivitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $queryArea = AreaKebun::query();
        
        $queryBibit = \App\Models\Pembibitan::query();
        $queryTanam = \App\Models\Tanam::query();

        if ($user->isPengurus()) {
            if (str_contains($user->email, 'kopi')) {
                $queryArea->where('id_komoditas', 1);
                $queryBibit->where('id_komoditas', 1);
                $queryTanam->whereHas('area', function ($q) { $q->where('id_komoditas', 1); });
            } elseif (str_contains($user->email, 'sawit')) {
                $queryArea->where('id_komoditas', 2);
                $queryBibit->where('id_komoditas', 2);
                $queryTanam->whereHas('area', function ($q) { $q->where('id_komoditas', 2); });
            } elseif (str_contains($user->email, 'lada')) {
                $queryArea->where('id_komoditas', 3);
                $queryBibit->where('id_komoditas', 3);
                $queryTanam->whereHas('area', function ($q) { $q->where('id_komoditas', 3); });
            }
        }

        $areas = $queryArea->get();
        
        // Calculate Siap Tanam specifically for the Tanam form
        $bibitSiapTanam = 0;
        foreach ($queryBibit->get() as $b) {
            $targetBulan = ($b->id_komoditas == 2) ? 12 : 6;
            $usiaAwal = (int) $b->usia_bibit;
            $bulanBerlalu = \Carbon\Carbon::parse($b->tanggal_pembibitan)->diffInMonths(\Carbon\Carbon::now());
            $usiaSekarang = (int) floor($usiaAwal + $bulanBerlalu);

            if ($usiaSekarang >= $targetBulan) {
                $bibitSiapTanam += $b->jumlah_bibit;
            }
        }

        $firstPembibitanDate = (clone $queryBibit)->min('tanggal_pembibitan');
        if ($firstPembibitanDate) {
            $queryTanam->where('tanggal_tanam', '>=', $firstPembibitanDate);
        }

        $totalDitanam = $queryTanam->sum('jumlah_tanam');
        $sisaStok = max(0, $bibitSiapTanam - $totalDitanam);

        return view('tanam.create', compact('areas', 'sisaStok'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_area' => 'required',
            'tanggal_tanam' => 'required|date',
            'jumlah_tanam' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $tanam = new Tanam();
        $tanam->id_area = $request->id_area;
        $tanam->id_peralatan = $request->id_peralatan ?? 1;
        $tanam->id_perlengkapan = $request->id_perlengkapan ?? 1;
        $tanam->tanggal_tanam = $request->tanggal_tanam;
        $tanam->jumlah_tanam = $request->jumlah_tanam;
        $tanam->keterangan = $request->keterangan;
        $tanam->save();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Catat Tanam Baru',
            'deskripsi' => Auth::user()->name . " melakukan penanaman/penyulaman {$tanam->jumlah_tanam} bibit di area " . ($tanam->area->nama_lahan ?? $tanam->area->nama_area)
        ]);
        return redirect()->route('tanam.index')->with('sukses', 'Data penanaman berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tanam $tanam)
    {
        $user = Auth::user();
        $queryArea = AreaKebun::query();

        if ($user->isPengurus()) {
            if (str_contains($user->email, 'kopi')) {
                $queryArea->where('id_komoditas', 1);
            } elseif (str_contains($user->email, 'sawit')) {
                $queryArea->where('id_komoditas', 2);
            } elseif (str_contains($user->email, 'lada')) {
                $queryArea->where('id_komoditas', 3);
            }
        }

        $areas = $queryArea->get();
        return view('tanam.edit', compact('tanam', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tanam $tanam)
    {
        $request->validate([
            'id_area' => 'required',
            'tanggal_tanam' => 'required|date',
            'jumlah_tanam' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $tanam->id_area = $request->id_area;
        $tanam->id_peralatan = $request->id_peralatan ?? 1;
        $tanam->id_perlengkapan = $request->id_perlengkapan ?? 1;
        $tanam->tanggal_tanam = $request->tanggal_tanam;
        $tanam->jumlah_tanam = $request->jumlah_tanam;
        $tanam->keterangan = $request->keterangan;
        $tanam->save();

        return redirect()->route('tanam.index')->with('sukses', 'Data penanaman berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tanam $tanam)
    {
        $tanam->delete();
        return redirect()->route('tanam.index')->with('sukses', 'Data penanaman berhasil dihapus!');
    }
}
