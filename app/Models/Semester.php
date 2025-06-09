<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class, 'id_semester');
    }
}
