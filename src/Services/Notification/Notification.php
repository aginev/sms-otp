<?php

namespace SmsOtp\Services\Notification;

use SmsOtp\Models\User;

interface Notification
{
    /**
     * Send notification message to the user
     *
     * @param \SmsOtp\Models\User $user
     * @param string $message
     */
    public function send(User $user, string $message): void;
}
