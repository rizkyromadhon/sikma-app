<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';

    protected $guarded = [];

    public $timestamps = false;

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_prodi');
    }
}
