<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public int $threadId;
    public string $senderName;
    public int $senderId;
    public int $recipientId;
    public int $messageId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $message, int $threadId, string $senderName, int $senderId, int $recipientId, int $messageId = null)
    {
        $this->message = $message;
        $this->threadId = $threadId;
        $this->senderName = $senderName;
        $this->senderId = $senderId;
        $this->recipientId = $recipientId;
        $this->messageId = $messageId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('messenger.thread.' . $this->threadId),
            new PrivateChannel('App.Models.User.' . $this->recipientId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}
