<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChildApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $child;

    /**
     * Create a new notification instance.
     */
    public function __construct($child)
    {
        $this->child = $child;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'child_id' => $this->child->id,
            'message' => 'Registration for ' . $this->child->first_name . ' ' . $this->child->last_name . ' has been approved.',
            'type' => 'child_approved',
            'link' => route('parent.child-profile'),
        ];
    }
}
