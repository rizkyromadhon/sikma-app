<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class AdminPasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    public $adminMessage;

    public function __construct($token, $adminMessage)
    {
        $this->token = $token;
        $this->adminMessage = $adminMessage;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Buat URL reset password menggunakan route default Laravel
        $resetUrl = url(route('password-baru', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // return (new MailMessage)
        //     ->subject(Lang::get('Reset Password Notification'))
        //     ->line(Lang::get('You are receiving this email because we received a password reset request for your account from an administrator.'))
        //     ->line(Lang::get('Admin Message:'))
        //     ->line($this->adminMessage ?: Lang::get('No additional message was provided.')) // Tampilkan pesan admin
        //     ->action(Lang::get('Reset Password'), $resetUrl)
        //     ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
        //     ->line(Lang::get('If you did not request a password reset, no further action is required.'));
        return (new MailMessage)
            ->subject('Pengaturan Ulang Kata Sandi untuk Akun Anda')
            ->greeting('Halo, ' . $notifiable->name . '!') // Sapa pengguna dengan namanya
            ->line('Kami menghubungi Anda karena admin telah memproses permintaan pengaturan ulang kata sandi untuk akun Anda.')
            ->line('Berikut adalah pesan dari admin:')
            // Menggunakan panel agar pesan admin lebih menonjol
            ->line($this->adminMessage ?: 'Tidak ada pesan tambahan yang diberikan.')
            ->action('Atur Ulang Kata Sandi', $resetUrl)
            ->line('Tautan pengaturan ulang kata sandi ini akan kedaluwarsa dalam ' . config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') . ' menit.')
            ->line('Jika Anda tidak merasa meminta perubahan ini, Anda dapat dengan aman mengabaikan email ini.')
            ->salutation('Hormat kami,');
    }
}
