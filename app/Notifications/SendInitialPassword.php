<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendInitialPassword extends Notification
{
    use Queueable;

    public function __construct(public string $password) {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $loginUrl = url('/login');

        return (new MailMessage)
            ->subject(sprintf('Acceso a %s', config('app.name')))
            ->greeting('Hola')
            ->line('Se ha creado una cuenta para usted. A continuación están las credenciales para iniciar sesión:')
            ->line('Email: '.$notifiable->email)
            ->line('Contraseña: '.$this->password)
            ->action('Iniciar sesión', $loginUrl)
            ->line('Por favor cambie su contraseña después de iniciar sesión por razones de seguridad.');
    }
}
