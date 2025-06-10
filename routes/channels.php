<?php

use App\Models\JadwalKuliah;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('kelas.{jadwalId}', function ($user, $jadwalId) {
    // Cari jadwal berdasarkan ID yang diberikan
    $jadwal = JadwalKuliah::find($jadwalId);

    // Jika jadwal tidak ada, tolak akses
    if (!$jadwal) {
        return false;
    }

    // Izinkan akses hanya jika ID user yang login SAMA DENGAN ID dosen di jadwal tersebut
    return (int) $user->id === (int) $jadwal->id_user;
});

Broadcast::channel('notifikasi-dosen.{dosenId}', function ($user, $dosenId) {
    // Hanya izinkan jika ID user yang sedang login SAMA DENGAN ID pada nama channel.
    return (int) $user->id === (int) $dosenId;
});

Broadcast::channel('notifikasi.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
