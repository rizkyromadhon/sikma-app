<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzin extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'pengajuan_izin';

    protected $casts = [
        'tanggal_izin' => 'date', // Otomatis mengubah string 'YYYY-MM-DD' menjadi objek Carbon
    ];

    protected $with = ['users', 'jadwalKuliah'];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function jadwalKuliah()
    {
        return $this->belongsTo(JadwalKuliah::class, 'id_jadwal_kuliah');
    }
}
