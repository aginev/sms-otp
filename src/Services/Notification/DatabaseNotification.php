<?php

namespace SmsOtp\Services\Notification;

use SmsOtp\Models\User;
use SmsOtp\Repositories\NotificationRepository;

class DatabaseNotification implements Notification
{
    public function send(User $user, string $message): void
    {
        NotificationRepository::make()->create([
            'user_id' => $user->id,
            'message' => $message,
        ]);
    }
}
