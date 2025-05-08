<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Golongan extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'golongan';

    protected $guarded = [];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'golongan_id'); // Assuming 'golongan_id' is the foreign key in users table
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi');
    }
}
