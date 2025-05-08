<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = "ruangan";

    public $timestamps = false;
    protected $guarded = [];

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }
}
