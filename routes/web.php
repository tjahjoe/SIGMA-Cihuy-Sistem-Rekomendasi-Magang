<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\LowonganMagangController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PeriodeMagangController as PeriodeMagangControllerAdmin;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Dosen\AktivitasController as AktivitasControllerDosen;
use App\Http\Controllers\Dosen\EvaluasiController;
use App\Http\Controllers\Dosen\KeahlianDosenController;
use App\Http\Controllers\Admin\MagangController as MagangControllerAdmin;
use App\Http\Controllers\Mahasiswa\AktivitasController as AktivitasControllerMahasiswa;
use App\Http\Controllers\Mahasiswa\DokumenController;
use App\Http\Controllers\Mahasiswa\KeahlianMahasiswaController;
use App\Http\Controllers\Mahasiswa\MagangController as MagangControllerMahasiswa;
use App\Http\Controllers\Dosen\MagangController as MagangControllerDosen;
use App\Http\Controllers\Admin\AkunController as AkunControllerAdmin;
use App\Http\Controllers\Mahasiswa\AkunController as AkunControllerMahasiswa;
use App\Http\Controllers\Dosen\AkunController as AkunControllerDosen;
use App\Http\Controllers\Mahasiswa\PengalamanController;
use App\Http\Controllers\Mahasiswa\PenilaianController;
use App\Http\Controllers\Mahasiswa\PeriodeMagangController as PeriodeMagangControllerMahasiswa;
use App\Http\Controllers\Mahasiswa\PreferensiLokasiMahasiswaController;
use App\Http\Controllers\Mahasiswa\PreferensiPerusahaanMahasiswaController;
use App\Http\Controllers\Mahasiswa\RekomendasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', [LandingPageController::class, 'index'])->name('dashboard');


Route::pattern('id', '[0-9]+');
Route::get('/main', [LandingPageController::class, 'index'])->name('main');
Route::get('/login', [LoginController::class, 'getLogin'])->name('login');
Route::post('/login', [LoginController::class, 'postLogin']);
Route::get('/logout', [LogoutController::class, 'getLogout'])->middleware('auth');

Route::get('/register', [LoginController::class, 'getRegister']);
Route::post('/register', [LoginController::class, 'postRegister'])->name('register');
Route::middleware(['auth'])->group(function () {
    Route::get('/', [LoginController::class, 'getDashoboard']);
    Route::middleware(['authorize:ADM'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [MagangControllerAdmin::class, 'getDashboard']);

        Route::prefix('profil')->group(function () {
            Route::get('/', [AkunControllerAdmin::class, 'getProfil']);

            Route::prefix('edit')->group(function () {
                Route::get('/', [AkunControllerAdmin::class, 'getEditProfil']);
                Route::post('/', [AkunControllerAdmin::class, 'putAkun']);
            });
        });

        Route::prefix('admin')->group(function () {
            Route::get('/', [AdminController::class, 'getAdmin']);
            Route::get('/tambah', [AdminController::class, 'getAddAdmin']);
            Route::post('/tambah', [AdminController::class, 'postAdmin']);
            Route::get('/detail/{id_akun}', [AdminController::class, 'getDetailAdmin']);
            Route::get('/edit/{id_akun}', [AdminController::class, 'getEditAdmin']);
            Route::post('/edit/{id_akun}', [AdminController::class, 'putAdmin']);
            Route::delete('/edit/{id_akun}', [AdminController::class, 'deleteAdmin']);
        });

        Route::prefix('mahasiswa')->group(function () {
            Route::get('/', [MahasiswaController::class, 'getMahasiswa']);
            Route::get('/tambah', [MahasiswaController::class, 'getAddMahasiswa']);
            Route::post('/tambah', [MahasiswaController::class, 'postMahasiswa']);
            Route::get('/tambah/excel', [MahasiswaController::class, 'getAddExcelMahasiswa']);
            Route::get('/unduh/excel', [MahasiswaController::class, 'getUnduhExcelMahasiswa']);
            Route::post('/tambah/excel', [MahasiswaController::class, 'postAddExcelMahasiswa']);
            Route::get('/detail/{id_akun}', [MahasiswaController::class, 'getDetailMahasiswa']);
            Route::get('/edit/{id_akun}', [MahasiswaController::class, 'getEditMahasiswa']);
            Route::post('/edit/{id_akun}', [MahasiswaController::class, 'putMahasiswa']);
            Route::delete('/edit/{id_akun}', [MahasiswaController::class, 'deleteMahasiswa']);
        });

        Route::prefix('dosen')->group(function () {
            Route::get('/', [DosenController::class, 'getDosen']);
            Route::get('/tambah', [DosenController::class, 'getAddDosen']);
            Route::post('/tambah', [DosenController::class, 'postDosen']);
            Route::get('/detail/{id_akun}', [DosenController::class, 'getDetailDosen']);
            Route::get('/edit/{id_akun}', [DosenController::class, 'getEditDosen']);
            Route::post('/edit/{id_akun}', [DosenController::class, 'putDosen']);
            Route::delete('/edit/{id_akun}', [DosenController::class, 'deleteDosen']);
        });

        Route::prefix('prodi')->group(function () {
            Route::get('/', [ProdiController::class, 'getProdi']);
            Route::get('/tambah', [ProdiController::class, 'getAddProdi']);
            Route::post('/tambah', [ProdiController::class, 'postProdi']);
            Route::get('/edit/{id_prodi}', [ProdiController::class, 'getEditProdi']);
            Route::post('/edit/{id_prodi}', [ProdiController::class, 'putProdi']);
            Route::delete('/edit/{id_prodi}', [ProdiController::class, 'deleteProdi']);
        });

        Route::prefix('perusahaan')->group(function () {
            Route::get('/', [PerusahaanController::class, 'getPerusahaan']);
            Route::get('/tambah', [PerusahaanController::class, 'getAddPerusahaan']);
            Route::post('/tambah', [PerusahaanController::class, 'postPerusahaan']);
            Route::get('/detail/{id_perusahaan}', [PerusahaanController::class, 'getDetailPerusahaan']);
            Route::get('/edit/{id_perusahaan}', [PerusahaanController::class, 'getEditPerusahaan']);
            Route::post('/edit/{id_perusahaan}', [PerusahaanController::class, 'putPerusahaan']);
            Route::delete('/edit/{id_perusahaan}', [PerusahaanController::class, 'deletePerusahaan']);
        });

        Route::prefix('lowongan')->group(function () {
            Route::get('/', [LowonganMagangController::class, 'getLowongan']);
            Route::get('/tambah', [LowonganMagangController::class, 'getAddLowongan']);
            Route::post('/tambah', [LowonganMagangController::class, 'postLowongan']);
            Route::get('/detail/{id_lowongan}', [LowonganMagangController::class, 'getDetailLowongan']);
            Route::get('/edit/{id_lowongan}', [LowonganMagangController::class, 'getEditLowongan']);
            Route::post('/edit/{id_lowongan}', [LowonganMagangController::class, 'putLowongan']);
            Route::delete('/edit/{id_lowongan}', [LowonganMagangController::class, 'deleteLowongan']);
        });

        Route::prefix('periode')->group(function () {
            Route::get('/', [PeriodeMagangControllerAdmin::class, 'getPeriode']);
            Route::get('/lowongan/{id_perusahaan}/{id_bidang}', [PeriodeMagangControllerAdmin::class, 'getPeriodeLowongan']);
            Route::get('/tambah', [PeriodeMagangControllerAdmin::class, 'getAddPeriode']);
            Route::post('/tambah', [PeriodeMagangControllerAdmin::class, 'postPeriode']);
            Route::get('/detail/{id_periode}', [PeriodeMagangControllerAdmin::class, 'getDetailPeriode']);
            Route::get('/edit/{id_periode}', [PeriodeMagangControllerAdmin::class, 'getEditPeriode']);
            Route::post('/edit/{id_periode}', [PeriodeMagangControllerAdmin::class, 'putPeriode']);
            Route::delete('/edit/{id_periode}', [PeriodeMagangControllerAdmin::class, 'deletePeriode']);
        });

        Route::prefix('kegiatan')->group(function () {
            Route::get('/', [MagangControllerAdmin::class, 'getkegiatan']);
            Route::get('/tambah', [MagangControllerAdmin::class, 'getAddkegiatan']);
            Route::post('/tambah', [MagangControllerAdmin::class, 'postkegiatan']);
            Route::get('/detail/{id_magang}', [MagangControllerAdmin::class, 'getDetailkegiatan']);
            Route::get('/edit/{id_magang}', [MagangControllerAdmin::class, 'getEditkegiatan']);
            Route::post('/edit/{id_magang}', [MagangControllerAdmin::class, 'putkegiatan']);
            Route::delete('/edit/{id_magang}', [MagangControllerAdmin::class, 'deletekegiatan']);
        });

    });

    Route::middleware(['authorize:MHS'])->prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [PeriodeMagangControllerMahasiswa::class, 'getDashboard']);

        Route::prefix('profil')->group(function () {
            Route::get('/', [AkunControllerMahasiswa::class, 'getProfil'])->name('mahasiswa.profil');

            Route::prefix('edit')->group(function () {
                Route::get('/', [AkunControllerMahasiswa::class, 'getEditProfil']);
                Route::post('/', [AkunControllerMahasiswa::class, 'putAkun']);

                Route::prefix('keahlian')->group(callback: function () {
                    Route::get('/tambah', [KeahlianMahasiswaController::class, 'getAddKeahlian']);
                    Route::post('/tambah', [KeahlianMahasiswaController::class, 'postKeahlian']);
                    Route::get('/edit/{id_keahlian}', [KeahlianMahasiswaController::class, 'getEditKeahlian']);
                    Route::post('/edit/{id_keahlian}', [KeahlianMahasiswaController::class, 'putEditKeahlian']);
                    Route::delete('{id_keahlian}/{prioritas}', [KeahlianMahasiswaController::class, 'deleteKeahlian']);
                });

                Route::prefix('pengalaman')->group(function () {
                    Route::get('/tambah', [PengalamanController::class, 'getAddPengalaman']);
                    Route::post('/tambah', [PengalamanController::class, 'postpengalaman']);
                    Route::get('/edit/{id_pengalaman}', [PengalamanController::class, 'getEditpengalaman']);
                    Route::post('/edit/{id_pengalaman}', [PengalamanController::class, 'putEditpengalaman']);
                    Route::delete('{id_pengalaman}', [PengalamanController::class, 'deletepengalaman']);
                });

                Route::prefix('dokumen')->group(callback: function () {
                    Route::get('/tambah', [DokumenController::class, 'getAddDokumen']);
                    Route::post('/tambah', [DokumenController::class, 'postDokumen']);
                    Route::get('/edit/{id_dokumen}', [DokumenController::class, 'getEditDokumen']);
                    Route::post('/edit/{id_dokumen}', [DokumenController::class, 'putEditDokumen']);
                    Route::delete('{id_dokumen}', [DokumenController::class, 'deleteDokumen']);
                });

                Route::post('/preferensi/perusahaan', [PreferensiPerusahaanMahasiswaController::class, 'postPreferensiPerusahaan']);
                Route::post('/preferensi/lokasi/{id_preferensi}', [PreferensiLokasiMahasiswaController::class, 'putPreferensiLokasi']);

            });
        });

        Route::prefix('aktivitas')->group(function () {
            Route::get('/', [AktivitasControllerMahasiswa::class, 'getMagangDiterima']);
            Route::get('/{id_magang}', [AktivitasControllerMahasiswa::class, 'getAktivitas']);
            Route::get('/{id_magang}/tambah', [AktivitasControllerMahasiswa::class, 'getAddAktivitas']);
            Route::post('/{id_magang}/tambah', [AktivitasControllerMahasiswa::class, 'postAktivitas']);
            Route::get('/{id_magang}/detail/{id_aktivitas}', [AktivitasControllerMahasiswa::class, 'getDetailAktivitas']);
            Route::get('/{id_magang}/edit/{id_aktivitas}', [AktivitasControllerMahasiswa::class, 'getEditAktivitas']);
            Route::post('/{id_magang}/edit/{id_aktivitas}', [AktivitasControllerMahasiswa::class, 'putAktivitas']);
            Route::delete('/{id_magang}/edit/{id_aktivitas}', [AktivitasControllerMahasiswa::class, 'deleteAktivitas']);
        });

        Route::prefix('riwayat')->group(function () {
            Route::get('/', [MagangControllerMahasiswa::class, 'indexRiwayat']);
            Route::get('/aktivitas/{id_magang}', [AktivitasControllerMahasiswa::class, 'getRiwayatAktivitas']);
            Route::get('/aktivitas/{id_magang}/detail/{id_aktivitas}', [AktivitasControllerMahasiswa::class, 'getRiwayatDetailAktivitas']);
            Route::get('/sertifikat/{id_magang}', [MagangControllerMahasiswa::class, 'downloadSertifikat'])
            ->name('penilaian.download');
        });

        Route::prefix('penilaian')->group(function () {
            Route::get('/', [PenilaianController::class, 'index']);
            Route::get('/{id_magang}', [PenilaianController::class, 'getPenilaian'])->name('penilaian.get');
            Route::post('/{id_magang}', [PenilaianController::class, 'postPenilaian'])->name('penilaian.post');
        });

        Route::prefix('periode')->group(function () {
            Route::get('/', [RekomendasiController::class, 'tampilkanHasilRekomendasi']);
            Route::get('/data', [PeriodeMagangControllerMahasiswa::class, 'getPeriodeData']);
            // Route::get('/rekomendasi', [RekomendasiController::class, 'tampilkanHasilRekomendasi']);
            Route::get('/rekomendasi/perhitungan', [RekomendasiController::class, 'prosesPerhitungan']);
            Route::get('/detail/{id_periode}', [PeriodeMagangControllerMahasiswa::class, 'getDetailPeriode']);
            Route::post('/{id_periode}', [MagangControllerMahasiswa::class, 'postMagang']);
        });
    });

    Route::middleware(['authorize:DSN'])->prefix('dosen')->group(function () {
        Route::get('/dashboard', [MagangControllerDosen::class, 'getRiwayat']);
        Route::prefix('profil')->group(function () {
            Route::get('/', [AkunControllerDosen::class, 'getProfil']);

            Route::prefix('edit')->group(function () {
                Route::get('/', [AkunControllerDosen::class, 'getEditProfil']);
                Route::post('/', [AkunControllerDosen::class, 'putAkun']);

                Route::prefix('keahlian')->group(function () {
                    Route::get('/', [KeahlianDosenController::class, 'getAddKeahlian']);
                    Route::get('list', [KeahlianDosenController::class, 'getKeahlianList']);
                    Route::get('{id_keahlian}', [KeahlianDosenController::class, 'getKeahlian']);
                    Route::post('/', [KeahlianDosenController::class, 'postKeahlian']);
                    Route::put('{id_keahlian}', [KeahlianDosenController::class, 'putKeahlian']);
                    Route::delete('{id_keahlian}', [KeahlianDosenController::class, 'deleteKeahlian']);
                });

            });
        });

        Route::prefix('aktivitas')->group(function () {
            Route::get('/', [AktivitasControllerDosen::class, 'getMagangDiterima']);
            Route::get('/{id_magang}', [AktivitasControllerDosen::class, 'getAktivitas']);
            Route::get('/detail/{id_magang}', [AktivitasControllerDosen::class, 'getDetail']);
            Route::get('/{id_magang}/profil', [AkunControllerDosen::class, 'getProfilMahasiswa']);
            Route::get('/{id_magang}/evaluasi', [EvaluasiController::class, 'getEvaluasi']);
            Route::post('/{id_magang}/evaluasi', [EvaluasiController::class, 'postEvaluasi']);
            Route::get('/{id_magang}/evaluasi/{id_evaluasi}', [EvaluasiController::class, 'getEditEvaluasi']);
            Route::put('/{id_magang}/evaluasi/{id_evaluasi}', [EvaluasiController::class, 'putEvaluasi']);
            Route::delete('/{id_magang}/evaluasi/{id_evaluasi}', [EvaluasiController::class, 'deleteEvaluasi']);
        });

        Route::prefix('riwayat')->group(function () {
            Route::get('/detail/{id_magang}', [MagangControllerDosen::class, 'getDetailRiwayat']);
        });
    });
});