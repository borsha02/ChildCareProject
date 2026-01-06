<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MedicationExpiredNotification extends Notification
{
    use Queueable;

    protected $medication;

    /**
     * Create a new notification instance.
     */
    public function __construct($medication)
    {
        $this->medication = $medication;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'medication_expired',
            'title' => 'Medication Period Ended',
            'message' => "The medication '{$this->medication->medication_name}' for child '{$this->medication->child->first_name}' has reached its finishing date and is now marked as completed.",
            'child_id' => $this->medication->child_id,
            'medication_id' => $this->medication->id,
            'icon' => 'fas fa-pills',
            'color' => 'orange'
        ];
    }
}
