<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class RfidForm extends Component
{
    public $uid;
    public $mahasiswaId;

    public function mount($mahasiswaId)
    {
        $this->mahasiswaId = $mahasiswaId;

        // Ambil nilai dari database user sebagai nilai awal
        $mahasiswa = User::find($mahasiswaId);

        // Jika ada validation error, gunakan old input
        // Jika tidak ada validation error tapi ada di database, gunakan nilai dari database
        // Jika keduanya tidak ada, kosongkan
        $this->uid = old('uid', $mahasiswa->uid ?? '');
    }

    public function pollCheck()
    {
        // Jika tidak ada old value dari validation error, maka bisa diupdate dari scan RFID baru
        if (!session()->hasOldInput('uid')) {
            $newUid = Cache::get('rfid-uid');

            if ($newUid && $newUid !== $this->uid) {
                $this->uid = $newUid;
            }
        }
    }

    public function render()
    {
        return view('livewire.rfid-form');
    }
}
