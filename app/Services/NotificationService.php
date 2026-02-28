<?php

namespace App\Services;

use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public static function send(string $email, string $message, string $subject): void
    {
        try {
            Mail::to($email)->send(new UserNotification($message, $subject));
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
        }
    }
}
