<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifications = Notifikasi::where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifikasi', [
            'notifications' => $notifications
        ]);
    }
    public function markAllAsRead()
    {
        // Query langsung ke model Notifikasi, tanpa melalui relasi User
        Notifikasi::where('id_user', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Kirim response success dalam format JSON
        return response()->json(['status' => 'success']);
    }

    public function markAllAsReadDosen()
    {
        // Query langsung ke model Notifikasi, tanpa melalui relasi User
        Notifikasi::where('id_user', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Kirim response success dalam format JSON
        return response()->json(['status' => 'success']);
    }

    /**
     * Menandai satu notifikasi sebagai terbaca, lalu mengarahkan ke URL tujuan.
     * Fungsi ini menerima objek Notifikasi secara otomatis berkat Route Model Binding.
     *
     * @param  \App\Models\Notifikasi  $notifikasi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function readAndRedirect(Notifikasi $notifikasi): RedirectResponse
    {
        if ($notifikasi->id_user !== Auth::id()) {
            abort(403, 'ANDA TIDAK MEMILIKI HAK AKSES UNTUK NOTIFIKASI INI.');
        }

        if (is_null($notifikasi->read_at)) {
            $notifikasi->update(['read_at' => now()]);
        }

        return redirect()->to($notifikasi->url_tujuan ?? '/');
    }
}
