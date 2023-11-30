<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected readonly string $password,
        protected readonly User $user,
        protected readonly string $company
    ) {

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
        return (new MailMessage)
            ->subject(env('APP_NAME').' - Created Account')
            ->greeting("Hello, {$this->user->name} {$this->user->surname}")
            ->line("Your account has been created by {$this->company}")
            ->line("To login use your email: {$this->user->email}")
            ->line("Your default password is: {$this->password}")
            ->line('To enhance security, it is recommended that you change your password to prevent potential issues')
            ->action('Visit site', env('FRONTEND_URL'));
        //->action('Notification Action', url('/'))

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
}
