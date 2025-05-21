<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan extends Model
{
    use HasFactory;
    protected $table = "ruangan";

    public $timestamps = false;
    protected $guarded = [];

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }
}
