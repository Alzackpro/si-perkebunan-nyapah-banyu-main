<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AreaKebunController;
use App\Http\Controllers\PanenController;
use App\Http\Controllers\OperasionalController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\SdmController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KomoditasController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PerlengkapanController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PembibitanController;
use App\Http\Controllers\TanamController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\HasilPengecekanController;
use App\Models\LogAktivitas;

// Route untuk menambah kolom operasional
Route::get('/tambah-kolom-operasional', function () {
    if (!Schema::hasColumn('operasional', 'jenis_tanaman')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->string('jenis_tanaman')->nullable()->after('area_kebun_id');
        });
        echo "Kolom jenis_tanaman ditambahkan!<br>";
    } else {
        echo "Kolom jenis_tanaman sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'jenis_operasional')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->string('jenis_operasional')->nullable()->after('jenis_tanaman');
        });
        echo "Kolom jenis_operasional ditambahkan!<br>";
    } else {
        echo "Kolom jenis_operasional sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'nama_pupuk')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->string('nama_pupuk')->nullable()->after('jadwal_operasional');
        });
        echo "Kolom nama_pupuk ditambahkan!<br>";
    } else {
        echo "Kolom nama_pupuk sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'nama_obat_hama')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->string('nama_obat_hama')->nullable()->after('nama_pupuk');
        });
        echo "Kolom nama_obat_hama ditambahkan!<br>";
    } else {
        echo "Kolom nama_obat_hama sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'jenis_bibit')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->string('jenis_bibit')->nullable()->after('nama_obat_hama');
        });
        echo "Kolom jenis_bibit ditambahkan!<br>";
    } else {
        echo "Kolom jenis_bibit sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'jumlah_bibit')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->integer('jumlah_bibit')->nullable()->default(0)->after('jenis_bibit');
        });
        echo "Kolom jumlah_bibit ditambahkan!<br>";
    } else {
        echo "Kolom jumlah_bibit sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'jumlah_tanaman_mati')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->integer('jumlah_tanaman_mati')->nullable()->default(0)->after('jumlah_bibit');
        });
        echo "Kolom jumlah_tanaman_mati ditambahkan!<br>";
    } else {
        echo "Kolom jumlah_tanaman_mati sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'jumlah_tanaman_diganti')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->integer('jumlah_tanaman_diganti')->nullable()->default(0)->after('jumlah_tanaman_mati');
        });
        echo "Kolom jumlah_tanaman_diganti ditambahkan!<br>";
    } else {
        echo "Kolom jumlah_tanaman_diganti sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'status_operasional')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->string('status_operasional')->nullable()->default('Jadwal')->after('jumlah_tanaman_diganti');
        });
        echo "Kolom status_operasional ditambahkan!<br>";
    } else {
        echo "Kolom status_operasional sudah ada!<br>";
    }
    
    if (!Schema::hasColumn('operasional', 'keterangan')) {
        Schema::table('operasional', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->after('status_operasional');
        });
        echo "Kolom keterangan ditambahkan!<br>";
    } else {
        echo "Kolom keterangan sudah ada!<br>";
    }
    
    echo "<br><hr><strong>SELESAI!</strong> Semua kolom berhasil diperiksa dan ditambahkan jika belum ada.<br>";
    echo "<a href='/operasional' class='btn btn-success mt-3' style='text-decoration: none; color: white;'>Kembali ke Halaman Operasional</a>";
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Publik: Mengarahkan halaman awal ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup Rute Terautentikasi (Auth)
Route::middleware(['auth'])->group(function () {

    // Halaman Dashboard Utama (Diakses semua role yang login)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman Pengaturan (Settings)
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    // =========================================================================
    // GRUP HAK AKSES BERSAMA (OWNER & PENGURUS LAHAN)
    // =========================================================================
    Route::middleware(['role:owner,pengurus'])->group(function () {
        
        // 1. Modul Panen (Owner bisa CRUD + Hapus, Pengurus hanya input & edit)
        Route::resource('panen', PanenController::class)->except(['destroy']);
        // Rute Hapus khusus dilindungi kontrol internal di PanenController agar bisa mengembalikan SweetAlert khusus
        Route::delete('panen/{panen}', [PanenController::class, 'destroy'])->name('panen.destroy');
        Route::patch('panen/{panen}/status', [PanenController::class, 'updateStatus'])->name('panen.updateStatus');

        // 2. Modul Operasional Kebun (Owner bisa CRUD + Hapus, Pengurus hanya input & edit)
        Route::resource('operasional', OperasionalController::class)->except(['destroy']);
        Route::delete('operasional/{operasional}', [OperasionalController::class, 'destroy'])->name('operasional.destroy');

        // 3. Modul Pengiriman Hasil Kebun ke Pabrik
        Route::resource('pengiriman', PengirimanController::class)->except(['destroy']);
        Route::delete('pengiriman/{pengiriman}', [PengirimanController::class, 'destroy'])->name('pengiriman.destroy');

        // 4. Modul Manajemen Area Kebun / Lahan
        Route::resource('area-kebun', AreaKebunController::class);

        // 5. Modul Operasional SDM Pekerja & Upah Pekerja
        Route::resource('sdm', SdmController::class);

        // 6. Modul Pembibitan Komoditas (Kopi, Sawit, Lada)
        Route::resource('pembibitan', PembibitanController::class);

        // 7. Modul Tanam (Penyulaman)
        Route::resource('tanam', TanamController::class);

        // 8. Modul Laporan Terpadu (Panen, Operasional, Pengiriman, SDM)
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan', [LaporanController::class, 'tampilkan'])->name('laporan.tampilkan');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
        Route::get('/laporan/unduh-pdf', [LaporanController::class, 'unduhPdf'])->name('laporan.unduh-pdf');
    });

    // =========================================================================
    // GRUP HAK AKSES EKSKLUSIF (HANYA OWNER)
    // =========================================================================
    Route::middleware(['role:owner'])->group(function () {
        
        // 6.5 Modul Master Data Pegawai, User, Komoditas, Peralatan, Perlengkapan, Status, Jadwal
        Route::resource('pegawai', PegawaiController::class);
        Route::resource('user', UserController::class);
        Route::resource('komoditas', KomoditasController::class);
        Route::resource('peralatan', PeralatanController::class);
        Route::resource('perlengkapan', PerlengkapanController::class);
        Route::resource('status', StatusController::class);
        Route::resource('jadwal', JadwalController::class);

        // 7. Modul Log Aktivitas Pengguna (Audit Trail)
        Route::get('/log-aktivitas', function () {
            $logAktivitas = LogAktivitas::with('user')->orderBy('created_at', 'desc')->paginate(20);
            return view('log_aktivitas.index', compact('logAktivitas'));
        })->name('log-aktivitas.index');

    });
});

// Load Breeze Authentication Routes
require __DIR__.'/auth.php';
