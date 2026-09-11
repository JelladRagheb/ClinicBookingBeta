<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
// use App\Models\Notification;
class CustomDatabaseChannel
{
    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification)
    {
        if (! method_exists($notification, 'toCustomDatabase')) {
            throw new \RuntimeException('Notification is missing toCustomDatabase method.');
        }
        /** @var mixed $notification */
        $data = $notification->toCustomDatabase($notifiable);
        return DB::table('notifications')->insert([
            // In the controller, we do $doctorProfile->user->notify().
            // So $notifiable is the User instance.
            'user_id' => $notifiable->id,
            'type' => get_class($notification),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'channel' => 'database',
            'title' => json_encode($data['title']),
            'message' => json_encode($data['message']),
            'action_url' => $data['action_url'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
