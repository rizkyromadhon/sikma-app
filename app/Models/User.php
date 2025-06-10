<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Notifikasi;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'nim',
    //     'program_studi',
    //     'kelas',
    //     'no_hp',
    //     'alamat',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_profile_complete' => 'boolean',
        ];
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class, 'jadwal_kuliah', 'id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_golongan');
    }

    public function notifications()
    {
        return $this->hasMany(Notifikasi::class);
    }
}
