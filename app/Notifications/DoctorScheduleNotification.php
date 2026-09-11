<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DoctorScheduleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;
    public $patientName;
    public $eventId;
    public $eventTitle;
    public $eventStart;
    public $eventEnd;

    /**
     * Create a new notification instance.
     * 
     * @param string $type ('created', 'updated', 'deleted')
     * @param array $eventData Data array containing id, title, start, end
     * @param string $patientName
     */
    public function __construct($type, $eventData, $patientName)
    {
        $this->type = $type;
        $this->patientName = $patientName;
        $this->eventId = $eventData['id'] ?? null;
        $this->eventTitle = $eventData['title'] ?? 'N/A';
        $this->eventStart = $eventData['start'] ?? 'N/A';
        $this->eventEnd = $eventData['end'] ?? 'N/A';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', \App\Channels\CustomDatabaseChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Appointment Update.')
            ->action('View Calendar', url('/calendar'))
            ->line('Thank you for using our application!');
    }

    /**
     * Custom Database schema mapping for the custom notifications table.
     */
    public function toCustomDatabase($notifiable): array
    {
        return [
            'title' => [
                'en' => 'Appointment Update',
                'fr' => 'Mise à jour de la demande',
                'ar' => 'تحديث الموعد',
            ],
            'message' => [
                'en' => $this->buildMessage(),
                'fr' => $this->buildMessage(),
                'ar' => $this->buildMessage(),
            ],
            'action_url' => '/calendar?date=' . \Carbon\Carbon::parse($this->eventStart)->format('Y-m-d') . '&event_id=' . $this->eventId
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toCustomDatabase($notifiable));
    }

    protected function buildMessage()
    {
        if ($this->type === 'created') {
            return "New booking by {$this->patientName} for '{$this->eventTitle}' from {$this->eventStart} to {$this->eventEnd}.";
        }
        if ($this->type === 'updated') {
            return "Booking '{$this->eventTitle}' updated by {$this->patientName} to {$this->eventStart} - {$this->eventEnd}.";
        }
        if ($this->type === 'deleted') {
            return "Booking '{$this->eventTitle}' for {$this->eventStart} was cancelled by {$this->patientName}.";
        }
        return "Appointment update for {$this->eventTitle}.";
    }
}
