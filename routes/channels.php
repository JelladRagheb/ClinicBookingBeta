<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('voice-call.user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('messenger.thread.{threadId}', function ($user, $threadId) {
    $thread = \Lexx\ChatMessenger\Models\Thread::find($threadId);
    return $thread && $thread->hasParticipant($user->id);
});
