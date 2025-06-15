<x-mail::message>
    # Balasan dari Admin

    Halo {{ $laporan->nama_lengkap }},

    Berikut adalah balasan dari admin terkait laporan Anda dengan tipe
    **"{{ Str::title(str_replace('_', ' ', $laporan->tipe)) }}"**.

    **Pesan dari Admin:**
    <x-mail::panel>
        {!! nl2br(e($laporan->balasan)) !!}
    </x-mail::panel>

    Jika balasan di atas berisi link untuk mengubah password, silakan klik link tersebut untuk melanjutkan.

    Terima kasih,<br>
    {{ config('app.name') }}
</x-mail::message>
