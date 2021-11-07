<?php

namespace SmsOtp\Services\Notification;

use SmsOtp\Models\User;

class NullNotification implements Notification
{
    public function send(User $user, string $message): void
    {
        //
    }
}
