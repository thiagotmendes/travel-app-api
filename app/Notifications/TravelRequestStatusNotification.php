<?php

namespace App\Notifications;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelRequestStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public TravelRequest $request) {}

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
            ->subject('Status do Pedido de Viagem Atualizado')
            ->greeting('Olá, ' . $notifiable->name)
            ->line("O status do seu pedido de viagem para {$this->request->destination} foi alterado para **{$this->request->status->value}**.")
            ->line('Obrigado por utilizar o sistema!');
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
