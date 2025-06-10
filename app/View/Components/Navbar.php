<?php

namespace App\View\Components;

use App\Models\Notifikasi;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public $notifications;
    public $unreadNotificationsCount;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Mengambil 5 notifikasi terbaru, sesuaikan dengan nama kolom Anda
            $this->notifications = Notifikasi::where('id_user', $user->id)
                ->latest() // Urutkan berdasarkan created_at terbaru
                ->take(5)
                ->get()
                ->map(function ($notif) {
                    // Buat properti baru 'created_at_human' untuk setiap notifikasi
                    $notif->created_at_human = $notif->created_at->diffForHumans();
                    return $notif;
                });

            // Menghitung notifikasi yang belum dibaca (dimana read_at masih NULL)
            $this->unreadNotificationsCount = Notifikasi::where('id_user', $user->id)
                ->whereNull('read_at')
                ->count();
        } else {
            // Set nilai default jika user belum login
            $this->notifications = collect(); // Koleksi kosong
            $this->unreadNotificationsCount = 0;
        }
    }

    /**
     * Get the view / contents that represent the component.
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}
