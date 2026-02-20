<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncidentUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
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
   public function toMail($notifiable)
{
    return (new \Illuminate\Notifications\Messages\MailMessage)
        ->subject('Mise à jour de votre signalement - CoproSmart')
        ->greeting('Bonjour ' . $notifiable->name . ' !')
        ->line('Le statut de votre incident "' . $this->incident->title . '" a été modifié.')
        ->line('Nouveau statut : **' . strtoupper($this->incident->status) . '**')
        ->line('Commentaire du syndic : ' . ($this->incident->admin_comment ?? 'Aucun commentaire.'))
        ->action('Voir mon incident', url('/dashboard'))
        ->line('Merci de votre confiance !');
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
