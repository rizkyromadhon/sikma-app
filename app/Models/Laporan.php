<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laporan extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id', 'nama', 'nim', 'prodi', 'email', 'pesan', 'status',
    // ];

    protected $table = 'laporan_mahasiswa';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi');
    }
}
