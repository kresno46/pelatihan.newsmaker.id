<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verify your Newsmaker account')
                ->greeting('Hello 👋')
                ->line('Thank you for joining Newsmaker.id.')
                ->line('To complete your registration and secure your account, please verify your email address by clicking the button below.')
                ->action('Verify Email Address', $url)
                ->line('This verification helps us confirm that this email address belongs to you.')
                ->line('If you did not create an account on Newsmaker.id, you can safely ignore this email.')
                ->salutation('Regards, Newsmaker Indonesia');
        });
    }
}
