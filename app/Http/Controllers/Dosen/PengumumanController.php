<?php

namespace App\Http\Controllers\Dosen;

use App\Events\NotifikasiMahasiswaBaru;
use App\Models\User;
use App\Models\Golongan;
use App\Models\Semester;
use App\Models\Notifikasi;
use App\Models\JadwalKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{

    public function index()
    {
        $dosenId = Auth::id();

        $jadwalOptions = JadwalKuliah::where('id_user', $dosenId)
            ->with('mataKuliah', 'golongan', 'prodi')
            ->get()
            ->unique(fn($j) => $j->id_matkul . '-' . $j->id_prodi)
            ->map(function ($jadwal) {
                $jadwal->display_name = $jadwal->mataKuliah->name . ' - ' . $jadwal->prodi->name;
                return $jadwal;
            });

        $semesterOptions = Semester::whereIn(
            'id',
            JadwalKuliah::where('id_user', $dosenId)
                ->distinct()
                ->pluck('id_semester')
        )
            ->get()
            ->map(function ($semester) {
                return (object)[
                    'id' => $semester->id,
                    'name' => $semester->name ?? $semester->display_name ?? 'Semester ' . $semester->id
                ];
            });

        $prodiOptions = ProgramStudi::whereIn(
            'id',
            JadwalKuliah::where('id_user', $dosenId)
                ->distinct()
                ->pluck('id_prodi')
        )->get();

        $golonganOptions = Golongan::whereIn(
            'id',
            JadwalKuliah::where('id_user', $dosenId)
                ->distinct()
                ->pluck('id_golongan')
        )->get();

        $totalProdiAjarIds = JadwalKuliah::where('id_user', $dosenId)->pluck('id_prodi')->unique();
        $totalSemesterAjarIds = JadwalKuliah::where('id_user', $dosenId)->pluck('id_semester')->unique();
        $riwayatPengumuman = DB::table('notifikasi')
            ->select('konten', 'tipe', 'url_tujuan', 'created_at', DB::raw('COUNT(id) as jumlah_penerima'))
            ->where('sender_id', $dosenId)
            ->groupBy('konten', 'tipe', 'url_tujuan', 'created_at')
            ->latest('created_at')
            ->limit(10)
            ->get();

        // 3. Untuk setiap grup, analisis penerimanya dan buat teks detail yang cerdas
        $riwayatPengumuman->each(function ($grup) use ($dosenId, $totalProdiAjarIds, $totalSemesterAjarIds) {
            $penerimaIds = DB::table('notifikasi')
                ->where('sender_id', $dosenId)
                ->where('konten', $grup->konten)
                ->where('tipe', $grup->tipe)
                ->where('created_at', $grup->created_at)
                ->when($grup->url_tujuan, fn($q, $url) => $q->where('url_tujuan', $url), fn($q) => $q->whereNull('url_tujuan'))
                ->pluck('id_user');

            // Asumsi data semester/prodi/golongan ada di tabel users
            $profilPenerima = DB::table('users')
                ->whereIn('id', $penerimaIds)
                ->select('id_prodi', 'id_semester', 'id_golongan')
                ->get();

            $detailParts = [];

            // --- Logika Cerdas untuk Prodi ---
            $prodiPenerimaIds = $profilPenerima->pluck('id_prodi')->unique()->filter();
            if ($prodiPenerimaIds->count() === $totalProdiAjarIds->count()) {
                $detailParts[] = 'Semua Program Studi';
            } elseif ($prodiPenerimaIds->count() > 0 && $prodiPenerimaIds->count() <= 2) {
                $detailParts[] = ProgramStudi::whereIn('id', $prodiPenerimaIds)->pluck('name')->implode(', ');
            } elseif ($prodiPenerimaIds->count() > 2) {
                $detailParts[] = $prodiPenerimaIds->count() . ' Program Studi';
            }

            // --- Logika Cerdas untuk Semester ---
            $semesterPenerimaIds = $profilPenerima->pluck('id_semester')->unique()->filter();
            if ($semesterPenerimaIds->count() === $totalSemesterAjarIds->count()) {
                $detailParts[] = 'Semua Semester';
            } elseif ($semesterPenerimaIds->count() > 0 && $semesterPenerimaIds->count() <= 2) {
                $detailParts[] =  Semester::whereIn('id', $semesterPenerimaIds)->pluck('display_name')->implode(', ');
            } elseif ($semesterPenerimaIds->count() > 2) {
                $detailParts[] = $semesterPenerimaIds->count() . ' Semester';
            }

            // --- Logika Cerdas untuk Golongan ---
            $golonganPenerimaIds = $profilPenerima->pluck('id_golongan')->unique()->filter();
            if ($golonganPenerimaIds->count() === 1) {
                $detailParts[] = 'Gol. ' . Golongan::find($golonganPenerimaIds->first())->nama_golongan;
            } elseif ($golonganPenerimaIds->count() > 1 && $golonganPenerimaIds->count() <= 3) {
                $detailParts[] = 'Gol. ' . Golongan::whereIn('id', $golonganPenerimaIds)->pluck('nama_golongan')->implode(', ');
            } elseif ($golonganPenerimaIds->count() > 3) {
                $detailParts[] = $golonganPenerimaIds->count() . ' Golongan';
            }

            $grup->target_detail_text = implode(' - ', $detailParts);
        });

        return view('dosen.pengumuman.index', compact(
            'semesterOptions',
            'prodiOptions',
            'golonganOptions',
            'riwayatPengumuman',
            'jadwalOptions'
        ));
    }


    /**
     * getMahasiswa - VERSI PERBAIKAN
     * Mendapatkan jumlah mahasiswa berdasarkan filter DAN jadwal dosen yang login.
     */
    public function getMahasiswa(Request $request)
    {
        try {
            $dosenId = Auth::id();
            $semester = $request->input('semester');
            $prodi = $request->input('prodi');
            $golongan = $request->input('golongan');

            // 1. Dapatkan dulu ID golongan yang relevan dari jadwal si dosen
            $jadwalQuery = JadwalKuliah::where('id_user', $dosenId);
            if ($semester && $semester !== 'all') {
                $jadwalQuery->where('id_semester', $semester);
            }
            if ($prodi && $prodi !== 'all') {
                $jadwalQuery->where('id_prodi', $prodi);
            }
            $golonganIdsFromJadwal = $jadwalQuery->pluck('id_golongan')->unique();

            if ($golonganIdsFromJadwal->isEmpty()) {
                return response()->json(['success' => true, 'count' => 0, 'detail' => 'Tidak ada mahasiswa yang Anda ajar pada filter ini.']);
            }

            // 2. Sekarang hitung mahasiswa berdasarkan ID golongan yang relevan
            $mahasiswaQuery = DB::table('users')->whereIn('id_golongan', $golonganIdsFromJadwal);

            // Terapkan filter golongan spesifik jika dipilih
            if ($golongan && $golongan !== 'all') {
                // Pastikan golongan yg dipilih termasuk yg diajar dosen
                if ($golonganIdsFromJadwal->contains($golongan)) {
                    $mahasiswaQuery->where('id_golongan', $golongan);
                } else {
                    // Jika tidak, hasilnya pasti 0
                    return response()->json(['success' => true, 'count' => 0, 'detail' => 'Anda tidak mengajar golongan ini.']);
                }
            }

            // Terapkan filter lain yang mungkin belum ter-cover oleh query jadwal (opsional, tapi bagus untuk konsistensi)
            if ($semester && $semester !== 'all') {
                $mahasiswaQuery->where('id_semester', $semester);
            }
            if ($prodi && $prodi !== 'all') {
                $mahasiswaQuery->where('id_prodi', $prodi);
            }

            $count = $mahasiswaQuery->count();
            $detail = $this->generateDetailMessage($count, $semester, $prodi, $golongan);

            return response()->json([
                'success' => true,
                'count' => $count,
                'detail' => $detail
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getMahasiswa: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat data mahasiswa.'], 500);
        }
    }

    /**
     * getGolonganOptions - VERSI PERBAIKAN
     * Mendapatkan opsi golongan berdasarkan semester/prodi yang DIAJAR oleh dosen.
     */
    public function getGolonganOptions(Request $request)
    {
        try {
            $dosenId = Auth::id();
            $semester = $request->input('semester');
            $prodi = $request->input('prodi');

            // Query golongan dari jadwal kuliah dosen, bukan dari tabel mahasiswa umum
            $query = Golongan::query()
                ->select('golongan.id', 'golongan.nama_golongan')
                ->whereIn('id', function ($q) use ($dosenId, $semester, $prodi) {
                    $q->select('id_golongan')
                        ->from('jadwal_kuliah')
                        ->where('id_user', $dosenId);

                    if ($semester && $semester !== 'all') {
                        $q->where('id_semester', $semester);
                    }

                    if ($prodi && $prodi !== 'all') {
                        $q->where('id_prodi', $prodi);
                    }
                })
                ->distinct()
                ->orderBy('nama_golongan');

            $golongan = $query->get();

            return response()->json([
                'success' => true,
                'golongan' => $golongan
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getGolonganOptions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat opsi golongan.'], 500);
        }
    }

    public function getJadwalOptions(Request $request)
    {
        try {
            $dosenId = Auth::id();
            $semesterId = $request->input('semester');
            $prodiId = $request->input('prodi');

            // 1. Ambil jadwal dosen yang relevan, pastikan memuat semua relasi
            $jadwalDosen = JadwalKuliah::where('id_user', $dosenId)
                ->with('mataKuliah', 'prodi', 'semester', 'golongan')
                ->when($semesterId && $semesterId !== 'all', fn($q) => $q->where('id_semester', $semesterId))
                ->when($prodiId && $prodiId !== 'all', fn($q) => $q->where('id_prodi', $prodiId))
                ->get();

            if ($jadwalDosen->isEmpty()) {
                return response()->json(['success' => true, 'jadwal' => []]);
            }

            // 2. Gunakan map() untuk mengubah setiap jadwal menjadi format yang diinginkan
            $jadwalOptions = $jadwalDosen->map(function ($jadwal) {

                $matkulName = $jadwal->mataKuliah->name ?? 'N/A';
                $semesterName = $jadwal->semester->display_name ?? 'N/A';
                $prodiName = $jadwal->prodi->name ?? 'N/A';

                $displayName = '';

                // Cek langsung ke kolom boolean 'is_kelas_besar'
                if ($jadwal->is_kelas_besar) {
                    // Jika ini adalah kelas besar
                    $displayName = "{$matkulName} - {$semesterName} - {$prodiName} - Semua Golongan";
                } else {
                    // Jika ini adalah kelas reguler per golongan
                    $golonganName = $jadwal->golongan->nama_golongan ?? 'N/A';
                    $displayName = "{$matkulName} - {$semesterName} - {$prodiName} - Gol. {$golonganName}";
                }

                return (object)[
                    'display_name' => $displayName,
                    'value'        => $displayName,
                    'short_name'   => $matkulName,
                ];
            })
                // 3. Gunakan unique() untuk menghapus duplikat (misal, ada beberapa jadwal kelas besar yang sama)
                ->unique('display_name')
                ->values(); // Reset keys array untuk output JSON yang bersih

            return response()->json([
                'success' => true,
                'jadwal' => $jadwalOptions
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getJadwalOptions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat opsi jadwal.'], 500);
        }
    }

    private function generateDetailMessage($count, $semester, $prodi, $golongan)
    {
        if ($count == 0) {
            return 'Tidak ada mahasiswa yang sesuai dengan filter';
        }

        $details = [];

        if ($semester && $semester !== 'all') {
            $semesterName = DB::table('semesters')->where('id', $semester)->value('display_name');
            if ($semesterName) {
                $details[] = "{$semesterName}";
            }
        }

        if ($prodi && $prodi !== 'all') {
            $prodiName = DB::table('program_studi')->where('id', $prodi)->value('name');
            if ($prodiName) {
                $details[] = $prodiName;
            }
        }

        if ($golongan && $golongan !== 'all') {
            $golonganName = DB::table('golongan')->where('id', $golongan)->value('nama_golongan');
            if ($golonganName) {
                $details[] = "Golongan {$golonganName}";
            }
        }

        $detailStr = empty($details) ? 'Semua mahasiswa yang Anda ajar' : implode(', ', $details);

        return "{$count} mahasiswa ({$detailStr}) akan menerima pengumuman";
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'semester' => 'required',
                'prodi' => 'required',
                'golongan' => 'required',
                'tipe' => 'required|string|max:255',
                'konten' => 'required|string|min:10|max:1000',
                'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
            ]);

            $dosenId = Auth::id();
            $pathLampiran = null;

            // Handle file upload
            if ($request->hasFile('file_lampiran')) {
                $pathLampiran = $request->file('file_lampiran')->store('lampiran-pengumuman', 'public');
            }

            if ($request->tipe === 'Materi Tambahan' && !$request->hasFile('file_lampiran')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Untuk tipe "Materi Tambahan", Anda wajib melampirkan file.');
            }

            // Query jadwal sesuai filter
            $jadwalQuery = JadwalKuliah::where('id_user', $dosenId);

            if ($request->semester !== 'all') {
                $jadwalQuery->where('id_semester', $request->semester);
            }

            if ($request->prodi !== 'all') {
                $jadwalQuery->where('id_prodi', $request->prodi);
            }

            $golonganIdsFromJadwal = $jadwalQuery->distinct()->pluck('id_golongan');

            if ($golonganIdsFromJadwal->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Tidak ada jadwal kuliah yang sesuai dengan filter yang dipilih.');
            }

            // Query mahasiswa
            $mahasiswaQuery = User::where('role', 'mahasiswa');

            if ($request->golongan !== 'all') {
                if ($golonganIdsFromJadwal->contains($request->golongan)) {
                    $mahasiswaQuery->where('id_golongan', $request->golongan);
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Golongan yang dipilih tidak sesuai dengan jadwal Anda.');
                }
            } else {
                $mahasiswaQuery->whereIn('id_golongan', $golonganIdsFromJadwal);
            }

            $mahasiswaIds = $mahasiswaQuery->pluck('id');

            if ($mahasiswaIds->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Tidak ada mahasiswa yang ditemukan untuk filter yang dipilih.');
            }

            // Buat notifikasi dalam batch
            DB::transaction(function () use ($mahasiswaIds, $dosenId, $request, $pathLampiran) {
                switch ($request->tipe) {
                    case 'Informasi Umum':
                        // Sesuai permintaan, biarkan null
                        $urlTujuan = null;
                        break;

                    case 'Tugas Baru':
                        // Arahkan ke URL E-Learning, ganti jika perlu
                        $urlTujuan = 'https://elearning-jti.polije.ac.id/';
                        break;

                    case 'Materi Tambahan':
                        // Buat URL download untuk file yang di-upload
                        if ($pathLampiran) {
                            $urlTujuan = asset('storage/' . $pathLampiran);
                        }
                        break;

                    case 'Perkuliahan Ditiadakan':
                        // Arahkan ke halaman jadwal kuliah mahasiswa
                        $urlTujuan = route('jadwal-kuliah.index'); // Ganti nama route jika berbeda
                        break;
                }

                $mahasiswas = User::whereIn('id', $mahasiswaIds)->get();
                $notifikasiBatch = [];
                $now = now();

                foreach ($mahasiswas as $mahasiswa) {
                    $dataUntukNotifikasi = [
                        'id_user' => $mahasiswa->id,
                        'sender_id' => $dosenId,
                        'tipe' => $request->tipe,
                        'konten' => $request->konten,
                        'url_tujuan' => $urlTujuan,
                        'read_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    $notifikasiBatch[] = $dataUntukNotifikasi;
                }

                if (!empty($notifikasiBatch)) {
                    Notifikasi::insert($notifikasiBatch);
                }

                // 3. Ambil kembali notifikasi yang BARU saja dibuat untuk di-dispatch
                // Ini penting untuk mendapatkan ID notifikasi dan timestamp yang konsisten
                $notifikasiBaru = Notifikasi::where('sender_id', $dosenId)
                    ->where('created_at', $now)
                    ->whereIn('id_user', $mahasiswaIds)
                    ->get();

                // Buat map untuk pencarian cepat: userId => notifikasi
                $notifikasiMap = $notifikasiBaru->keyBy('id_user');

                // 4. Loop lagi melalui objek mahasiswa untuk dispatch event
                foreach ($mahasiswas as $mahasiswa) {
                    // Cek apakah ada notifikasi yang sesuai di map
                    if (isset($notifikasiMap[$mahasiswa->id])) {
                        $notifikasiUntukEvent = $notifikasiMap[$mahasiswa->id];
                        NotifikasiMahasiswaBaru::dispatch($mahasiswa, $notifikasiUntukEvent);
                    }
                }
            });

            $jumlahTerkirim = $mahasiswaIds->count();

            return redirect()->route('dosen.pengumuman.index')
                ->with('success', "Pengumuman berhasil dikirim ke {$jumlahTerkirim} mahasiswa!");
        } catch (\Exception $e) {
            Log::error("Error in store pengumuman", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim pengumuman: ' . $e->getMessage());
        }
    }
}
