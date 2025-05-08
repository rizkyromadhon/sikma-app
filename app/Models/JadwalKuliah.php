<?php

namespace App\Models;

use App\Models\Kelas;
use App\Models\Presensi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalKuliah extends Model
{
    use HasFactory;
    // Menonaktifkan timestamps
    public $timestamps = false;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'jadwal_kuliah';

    protected $guarded = [];

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_golongan', 'id');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_jadwal_kuliah');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id');
    }
}
