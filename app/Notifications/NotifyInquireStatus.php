<?php

namespace App\Notifications;

use App\Models\Inquire;
use App\Models\InquireType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyInquireStatus extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Inquire $inquire,
        public readonly string $statusName,
        public readonly User $user,
        public readonly InquireType $type
    ) {
        //
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
            ->subject(env('APP_NAME').' - Inquire Status Changed')
            ->greeting("Hello, {$this->user->name} {$this->user->surname}")
            ->line("The status of your request for  {$this->type->friendly_name}
            on following dates: {$this->inquire->start} {$this->inquire->end} has been changed")
            ->line("The status of your request is: {$this->statusName}");
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
