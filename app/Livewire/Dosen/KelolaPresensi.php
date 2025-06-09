<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\JadwalKuliah;
use App\Models\Presensi;
use App\Models\PresensiKuliah;
use App\Models\User;

class KelolaPresensi extends Component
{
    public string $selectedDateString;
    public $dosenId;

    #[On('kehadiran-diperbarui')]
    public function refreshComponent()
    {
        // Sederhana: hanya log untuk debugging
        Log::info('Refreshing presensi data for dosen: ' . $this->dosenId);
        // Livewire akan otomatis re-render component
    }

    public function mount($selectedDate, $dosenId)
    {
        $this->selectedDateString = $selectedDate->format('Y-m-d');
        $this->dosenId = $dosenId;

        // Debug: Log mount
        Log::info('KelolaPresensi mounted with date: ' . $this->selectedDateString . ' dosen: ' . $this->dosenId);
    }

    public function render()
    {
        try {
            $selectedDate = Carbon::parse($this->selectedDateString);
            $namaHariDipilih = $selectedDate->locale('id')->translatedFormat('l');

            // Debug: Log query parameters
            Log::info('Querying jadwal with:', [
                'dosen_id' => $this->dosenId,
                'hari' => $namaHariDipilih,
                'tanggal' => $this->selectedDateString
            ]);

            // Query dengan error handling
            $jadwalPadaHariItu = JadwalKuliah::with([
                'mataKuliah' => function ($query) {
                    $query->select('id', 'name');
                },
                'ruangan' => function ($query) {
                    $query->select('id', 'name');
                },
                'prodi' => function ($query) {
                    $query->select('id', 'name');
                },
                'semester' => function ($query) {
                    $query->select('id', 'display_name');
                },
                'golongan' => function ($query) {
                    $query->select('id', 'nama_golongan');
                }
            ])
                ->where('id_user', $this->dosenId)
                ->where('hari', $namaHariDipilih)
                ->orderBy('jam_mulai', 'asc')
                ->get();

            // Debug: Log hasil query
            Log::info('Jadwal ditemukan: ' . $jadwalPadaHariItu->count());

            // Validasi: pastikan data ada
            if ($jadwalPadaHariItu->isEmpty()) {
                Log::warning('Tidak ada jadwal ditemukan untuk dosen ' . $this->dosenId . ' pada hari ' . $namaHariDipilih);
                return view('livewire.dosen.kelola-presensi', [
                    'jadwalDosen' => collect([]),
                    'selectedDate' => $selectedDate,
                ]);
            }

            // Group jadwal dengan handling yang lebih robust
            $grupJadwalSama = $jadwalPadaHariItu->groupBy(function ($jadwal) {
                // Pastikan mataKuliah exist sebelum akses
                $mataKuliahId = optional($jadwal->mataKuliah)->id ?? 'unknown_' . $jadwal->id;
                return $mataKuliahId . '-' . $jadwal->jam_mulai;
            });

            $jadwalDosen = $grupJadwalSama->map(function ($grup) use ($selectedDate) {
                $jadwalUtama = $grup->first();
                if (!$jadwalUtama) {
                    Log::warning('Jadwal utama tidak ditemukan dalam grup');
                    return null;
                }

                try {
                    $jadwalIds = $grup->pluck('id')->toArray();

                    // Hitung total mahasiswa dengan error handling
                    $totalMahasiswaKelas = 0;
                    $grupKelasUnik = $grup->unique(function ($item) {
                        return $item->id_prodi . '-' . $item->id_semester . '-' . ($item->id_golongan ?? 'null');
                    });

                    foreach ($grupKelasUnik as $g) {
                        try {
                            $query = User::where('role', 'mahasiswa')
                                ->where('id_prodi', $g->id_prodi)
                                ->where('id_semester', $g->id_semester);

                            if ($g->golongan && $g->golongan->nama_golongan !== 'Semua Golongan') {
                                $query->where('id_golongan', $g->id_golongan);
                            }
                            $totalMahasiswaKelas += $query->count();
                        } catch (\Exception $e) {
                            Log::error('Error counting mahasiswa: ' . $e->getMessage());
                        }
                    }

                    // Hitung kehadiran dengan error handling
                    $jumlahHadir = 0;
                    try {
                        $jumlahHadir = Presensi::whereIn('id_jadwal_kuliah', $jadwalIds)
                            ->whereDate('tanggal', $selectedDate)
                            ->where('status', 'Hadir')
                            ->distinct('user_id')
                            ->count();
                    } catch (\Exception $e) {
                        Log::error('Error counting presensi: ' . $e->getMessage());
                    }

                    // Status kelas
                    $jamMulai = Carbon::parse($selectedDate->toDateString() . ' ' . $jadwalUtama->jam_mulai);
                    $jamSelesai = Carbon::parse($selectedDate->toDateString() . ' ' . $jadwalUtama->jam_selesai);
                    $statusKelas = now()->between($jamMulai, $jamSelesai) ? 'Berlangsung' : (now()->isAfter($jamSelesai) || $selectedDate->isPast() ? 'Selesai' : 'Akan Datang');

                    // Persentase kehadiran
                    $persentaseKehadiran = ($totalMahasiswaKelas > 0) ?
                        ($jumlahHadir / $totalMahasiswaKelas) * 100 : 0;

                    // Golongan string dengan null safety
                    $golonganString = $grup->map(function ($item) {
                        return optional($item->golongan)->nama_golongan;
                    })->filter()->unique()->sort()->join(', ');

                    // Return data untuk view dengan null safety
                    return [
                        'id' => $jadwalUtama->id,
                        'jam_mulai' => $jadwalUtama->jam_mulai,
                        'jam_selesai' => $jadwalUtama->jam_selesai,
                        'status_kelas' => $statusKelas,
                        'nama_matkul' => optional($jadwalUtama->mataKuliah)->name ?? 'N/A',
                        'nama_ruangan' => optional($jadwalUtama->ruangan)->name ?? 'N/A',
                        'display_semester' => optional($jadwalUtama->semester)->display_name ?? 'N/A',
                        'nama_prodi' => optional($jadwalUtama->prodi)->name ?? 'N/A',
                        'is_kelas_besar' => $grup->count() > 1,
                        'semua_golongan_string' => $golonganString ?: 'N/A',
                        'jumlah_hadir' => $jumlahHadir,
                        'total_mahasiswa_kelas' => $totalMahasiswaKelas,
                        'persentase_kehadiran' => $persentaseKehadiran,
                    ];
                } catch (\Exception $e) {
                    Log::error('Error processing jadwal group: ' . $e->getMessage());
                    return null;
                }
            })->filter()->values();

            // Debug: Log hasil akhir
            Log::info('Jadwal processed: ' . $jadwalDosen->count());

            return view('livewire.dosen.kelola-presensi', [
                'jadwalDosen' => $jadwalDosen,
                'selectedDate' => $selectedDate,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in KelolaPresensi render: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Return empty state jika ada error
            return view('livewire.dosen.kelola-presensi', [
                'jadwalDosen' => collect([]),
                'selectedDate' => now(),
            ]);
        }
    }
}
