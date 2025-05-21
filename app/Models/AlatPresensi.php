<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlatPresensi extends Model
{
    public $timestamps = false;

    protected $table = 'alat_presensi';

    protected $guarded = [];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
}
