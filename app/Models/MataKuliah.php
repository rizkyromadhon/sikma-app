<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    public $guarded = [];

    public $timestamps = false;

    protected $table = "mata_kuliah";

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_matkul');
    }
}
