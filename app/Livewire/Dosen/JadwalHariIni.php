<?php

namespace App\Livewire\Dosen;

use App\Models\JadwalKuliah;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class JadwalHariIni extends Component
{
    // Properti ini akan otomatis tersedia di file view
    public $jadwalHariIni;

    /**
     * mount() dieksekusi saat komponen pertama kali dimuat.
     * Kita bisa memuat data awal di sini.
     */
    public function mount()
    {
        $this->loadJadwal();
    }

    /**
     * Fungsi untuk mengambil data jadwal dari database.
     */
    public function loadJadwal()
    {
        $dosenId = Auth::id();

        // Menggunakan Carbon untuk mendapatkan nama hari dalam bahasa Indonesia
        $namaHariIni = Carbon::now()->locale('id_ID')->dayName;

        $jadwalDariDb = JadwalKuliah::where('id_user', $dosenId)
            ->where('hari', $namaHariIni)
            ->with(['mataKuliah', 'ruangan', 'golongan.programStudi', 'semester', 'golongan' => function ($query) {
                $query->withCount('users');
            }])
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $this->jadwalHariIni = $jadwalDariDb->sortBy(function ($jadwal) {
            $now = now();
            $jamMulai = Carbon::parse($jadwal->jam_mulai);
            $jamSelesai = Carbon::parse($jadwal->jam_selesai);

            if ($now->between($jamMulai, $jamSelesai)) {
                return 1; // Prioritas 1: Berlangsung
            } elseif ($now->lt($jamMulai)) {
                return 2; // Prioritas 2: Akan Datang / Segera Dimulai
            } else {
                return 3; // Prioritas 3: Selesai
            }
        });

        $grupJadwal = $jadwalDariDb->groupBy(function ($jadwal) {
            return $jadwal->id_matkul . '_' . $jadwal->jam_mulai;
        });

        $jadwalTampil = $grupJadwal->map(function ($grup) {
            $jadwalPertama = $grup->first();
            $totalMahasiswa = $grup->sum(fn($j) => $j->golongan->users_count ?? 0);
            $namaGolongan = $grup->pluck('golongan.nama_golongan')->implode(', ');

            return (object) [
                'mataKuliah' => $jadwalPertama->mataKuliah,
                'jam_mulai' => $jadwalPertama->jam_mulai,
                'jam_selesai' => $jadwalPertama->jam_selesai,
                'ruangan' => $jadwalPertama->ruangan,
                'prodi' => optional($jadwalPertama->golongan)->programStudi,
                'semester' => $jadwalPertama->semester, // UBAH INI: Ambil semester langsung dari jadwal
                'nama_golongan_grup' => $namaGolongan,
                'total_mahasiswa' => $totalMahasiswa
            ];
        });

        $this->jadwalHariIni = $jadwalTampil->sortBy(function ($jadwal) {
            $now = now();
            $jamMulai = Carbon::parse($jadwal->jam_mulai);
            $jamSelesai = Carbon::parse($jadwal->jam_selesai);

            if ($now->between($jamMulai, $jamSelesai)) return 1;
            if ($now->lt($jamMulai)) return 2;
            return 3;
        });
    }

    /**
     * render() akan menampilkan file view dan mengirimkan data ke sana.
     * Livewire akan memanggil ini setiap kali ada pembaruan.
     */
    public function render()
    {
        // Setiap kali komponen di-render ulang (karena polling),
        // kita bisa saja memuat ulang jadwal jika ada kemungkinan perubahan.
        // Namun, untuk jadwal harian, memuat di mount() sudah cukup.
        return view('livewire.dosen.jadwal-hari-ini');
    }
}
