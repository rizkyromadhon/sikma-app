<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi_kuliah';

    // protected $fillable = ['user_id', 'tanggal', 'id_matkul', 'status', 'keterangan', 'waktu_presensi', 'id_jadwal_kuliah'];

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'waktu_presensi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'id_jadwal_kuliah');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id'); // Relasi ke MataKuliah
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class); // Relasi ke Mahasiswa
    }
}
