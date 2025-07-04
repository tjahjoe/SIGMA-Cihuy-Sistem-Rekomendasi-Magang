<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMagangModel;
use App\Models\AkunModel;
use App\Models\EvaluasiMagangModel;
use App\Models\MagangModel;
use App\Models\PeriodeMagangModel;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use Storage;
use Validator;

class AktivitasController extends Controller
{
    // add try catch and transaction
    private function idMahasiswa()
    {
        $id_mahasiswa = AkunModel::with(relations: 'mahasiswa:id_mahasiswa,id_akun')
            ->where('id_akun', Auth::user()->id_akun)
            ->first(['id_akun', 'id_level'])
            ->mahasiswa
            ->id_mahasiswa;
        return $id_mahasiswa;
    }

    public function getMagangDiterima()
    {
        $id_mahasiswa = $this->idMahasiswa();
        $magang = MagangModel::where('id_mahasiswa', $id_mahasiswa)
            ->where('status', 'diterima')
            ->with(
                'periode_magang:id_periode,id_lowongan,nama,tanggal_mulai,tanggal_selesai',
                'periode_magang.lowongan_magang:id_lowongan,id_perusahaan,id_bidang,nama',
                'periode_magang.lowongan_magang.perusahaan:id_perusahaan,id_jenis,nama',
                'periode_magang.lowongan_magang.bidang:id_bidang,nama',
                'periode_magang.lowongan_magang.perusahaan.jenis_perusahaan:id_jenis,jenis'
            )
            ->first();

        return view('mahasiswa.aktivitas.magang', ['magang' => $magang]);
    }
    public function getAktivitas($id_magang)
    {
        try {
            return DB::transaction(function () use ($id_magang) {
                $id_mahasiswa = $this->idMahasiswa();

                $magang = MagangModel::with('dosen.akun')
                    ->where('id_magang', $id_magang)
                    ->first();

                if ($magang) {
                    if ($magang->id_mahasiswa != $id_mahasiswa || $magang->status != 'diterima') {
                        return view('mahasiswa.aktivitas.index');
                    }
                } else {
                    return view('mahasiswa.aktivitas.index');
                }

                $aktivitas = AktivitasMagangModel::with('magang')
                    ->where('id_magang', $id_magang)
                    ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                        $query->where('id_mahasiswa', $id_mahasiswa);
                    })
                    ->orderByDesc('tanggal')
                    ->get();

                $today = Carbon::now()->toDateString();

                $hasActivityToday = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
                    ->where('id_magang', $id_magang)
                    ->where(function ($query) use ($today) {
                        $query->where('tanggal', $today);
                    })
                    ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                        $query->where('id_mahasiswa', $id_mahasiswa);
                    })
                    ->exists();

                return view('mahasiswa.aktivitas.index', [
                    'aktivitas' => $aktivitas,
                    'id_magang' => $id_magang,
                    'hasActivityToday' => $hasActivityToday,
                    'magang' => $magang
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal mengambil aktivitas magang: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }


    public function getDetailAktivitas($id_magang, $id_aktivitas)
    {
        $aktivitas = AktivitasMagangModel::where('id_magang', $id_magang)
            ->where('id_aktivitas', $id_aktivitas)
            ->first();

        return view('mahasiswa.aktivitas.detail', ['aktivitas' => $aktivitas]);
    }

    public function getAddAktivitas($id_magang)
    {
        return view('mahasiswa.aktivitas.tambah', ['id_magang' => $id_magang]);
    }

    public function getEditAktivitas($id_magang, $id_aktivitas)
    {
        try {
            return DB::transaction(function () use ($id_magang, $id_aktivitas) {
                $id_mahasiswa = $this->idMahasiswa();

                $aktivitas = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
                    ->where('id_aktivitas', $id_aktivitas)
                    ->where('id_magang', $id_magang)
                    ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                        $query->where('id_mahasiswa', $id_mahasiswa);
                    })
                    ->first();

                return view('mahasiswa.aktivitas.edit', [
                    'id_magang' => $id_magang,
                    'id_aktivitas' => $id_aktivitas,
                    'aktivitas' => $aktivitas
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data edit aktivitas: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }


    public function postAktivitas(Request $request, $id_magang)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $validator = Validator::make($request->all(), [
                    'file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                    'keterangan' => 'required'
                ]);

                if (!$request->hasFile('file')) {
                    return ['success' => false, 'message' => 'Foto kegiatan Harus Diisi!!!'];   
                }

                $file = $request->file('file');
                $max_size = 2 * 1024 * 1024;

                if ($file->getSize() > $max_size) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file tidak boleh lebih dari 2MB.']);
                }

                if ($validator->fails()) {
                    return response()->json(['success' => false]);
                }
                $keterangan = $request->input('keterangan');
                $date = Carbon::parse(now())->toDateString();
                $filename = null;

                DB::transaction(function () use ($request, $id_magang, &$filename, $keterangan, $date) {
                    if ($request->hasFile('file')) {
                        $file = $request->file('file');
                        $id_mahasiswa = $this->idMahasiswa();

                        $filename = $id_magang . '_' . $date . '.' . $file->getClientOriginalExtension();

                        AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
                            ->where('id_magang', $id_magang)
                            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                                $query->where('id_mahasiswa', $id_mahasiswa);
                            })->create([
                                    'id_magang' => $id_magang,
                                    'tanggal' => $date,
                                    'keterangan' => $keterangan,
                                    'foto_path' => $filename
                                ]);

                        $file->storeAs('public/aktivitas', $filename);
                    }
                });

                return response()->json([
                    'success' => true,
                    'data' => [
                        'keterangan' => $keterangan,
                        'foto_path' => $filename,
                        'tanggal' => $date,
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::error("Gagal menambahkan Aktivitas: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function putAktivitas(Request $request, $id_magang, $id_aktivitas)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $validator = Validator::make($request->all(), [
                    'keterangan' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                $result = DB::transaction(function () use ($request, $id_magang, $id_aktivitas) {
                    $id_mahasiswa = $this->idMahasiswa();
                    $data = AktivitasMagangModel::where('id_aktivitas', $id_aktivitas)
                        ->where('id_magang', $id_magang)
                        ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                            $query->where('id_mahasiswa', $id_mahasiswa);
                        })
                        ->first();

                    if (!$data) {
                        throw new \Exception("Data aktivitas tidak ditemukan atau tidak punya akses.");
                    }

                    $activityDate = Carbon::parse($data->tanggal)->startOfDay();
                    $today = Carbon::now()->startOfDay();
                    if ($activityDate->lt($today)) {
                        throw new \Exception("Aktivitas tanggal sebelumnya tidak dapat diubah.");
                    }

                    $data->keterangan = $request->input('keterangan');

                    if ($request->hasFile('file')) {
                        $file = $request->file('file');
                        $max_size = 2 * 1024 * 1024;

                        if ($file->getSize() > $max_size) {
                            return response()->json(['success' => false, 'message' => 'Ukuran file tidak boleh lebih dari 2MB.']);
                        }

                        $filename = $id_magang . '_' . Carbon::now()->toDateString() . '.' . $file->getClientOriginalExtension();

                        $file->storeAs('public/aktivitas', $filename);
                        $data->foto_path = $filename;
                    }

                    $data->save();

                    return [
                        'id_aktivitas' => $data->id_aktivitas,
                        'keterangan' => $data->keterangan,
                        'foto_path' => $data->foto_path,
                        'tanggal' => $data->tanggal,
                    ];
                });

                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Aktivitas: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }



    private function handleFileUpload(Request $request, $data, $id_aktivitas, $id_magang, $id_mahasiswa, $keterangan)
    {

        $file = $request->file('file');
        $date = Carbon::parse(now())->toDateString();
        $filename = $id_magang . '_' . $date . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->delete("aktivitas/{$data->foto_path}");
        $file->storeAs('public/aktivitas', $filename);
        AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
            ->where('id_aktivitas', $id_aktivitas)
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->update([
                'keterangan' => $keterangan,
                'foto_path' => $filename
            ]);
    }

    public function deleteAktivitas(Request $request, $id_magang, $id_aktivitas)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                DB::transaction(
                    function () use ($request, $id_magang, $id_aktivitas) {
                        $id_mahasiswa = $this->idMahasiswa();
                        $data = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
                            ->where('id_aktivitas', $id_aktivitas)
                            ->where('id_magang', $id_magang)
                            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                                $query->where('id_mahasiswa', $id_mahasiswa);
                            })
                            ->firstOrFail(['foto_path']);

                        $file_path = $data->foto_path;

                        if (Storage::disk('public')->exists("aktivitas/$file_path")) {
                            Storage::disk('public')->delete("aktivitas/$file_path");
                        }

                        AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
                            ->where('id_aktivitas', $id_aktivitas)
                            ->where('id_magang', $id_magang)
                            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                                $query->where('id_mahasiswa', $id_mahasiswa);
                            })
                            ->delete();
                    }
                );
                return response()->json(['success' => true]);
            } catch (\Throwable $e) {
                Log::error("Gagal update Aktivitas: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
            }
        }
    }

    public function getRiwayatAktivitas($id_magang)
    {
        $id_mahasiswa = $this->idMahasiswa();
        $magang = MagangModel::with('dosen.akun')
            ->where('id_magang', $id_magang)
            ->first();

        if ($magang) {
            if ($magang->id_mahasiswa != $id_mahasiswa || $magang->status != 'lulus') {
                return view('mahasiswa.riwayat.index-aktivitas');
            }
        } else {
            return view('mahasiswa.riwayat.index-aktivitas');
        }


        $aktivitas = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->orderByDesc('tanggal')
            ->get();

        $evaluasi = EvaluasiMagangModel::with('magang:id_magang,id_mahasiswa')
            ->where('id_magang', $id_magang)
            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->first();

        return view('mahasiswa.riwayat.index-aktivitas', ['aktivitas' => $aktivitas, 'evaluasi' => $evaluasi, 'magang' => $magang]);

        // return response()->json($magang);
    }
    public function getRiwayatDetailAktivitas($id_magang, $id_aktivitas)
    {
        $id_mahasiswa = $this->idMahasiswa();

        $aktivitas = AktivitasMagangModel::with('magang:id_magang,id_mahasiswa')
            ->where('id_magang', $id_magang)
            ->where('id_aktivitas', $id_aktivitas)
            ->whereHas('magang', function ($query) use ($id_mahasiswa) {
                $query->where('id_mahasiswa', $id_mahasiswa);
            })
            ->first();

        return view('mahasiswa.riwayat.detail-aktivitas', ['aktivitas' => $aktivitas]);
    }
}
