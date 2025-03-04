<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailCriacaoSenha extends Notification
{
    use Queueable;

    public static $createUrlCallback;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $token)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $expire = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
        $url    = $this->resetUrl($notifiable);

        return (new MailMessage())
            ->subject('Notificação de criação de senha')
            ->greeting('Criação de senha')
            ->line('Você está recebendo este e-mail para criar uma senha para sua conta.')
            ->action('Criar senha', $url)
            ->line("Este link de redefinição de senha irá expirar em $expire minutos")
            ->line('Caso expire o link, por favor solicite uma nova senha.')
            ->salutation('presados');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
