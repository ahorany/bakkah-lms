<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class TwoFactorCode extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    // ->line('Your two factor code is '.$notifiable->two_factor_code)
                    // // ->line('The introduction to the notification.')
                    // ->action('Notification Action', url('/'))
                    // ->line('Thank you for using our application!');
                    // ->line('Your two factor code is <strong>'.$notifiable->two_factor_code.'</strong>')

                    ->subject('Verification Code')
                    ->line(new HtmlString('Your two factor code is <strong>' . $notifiable->two_factor_code . '</strong>'))
                    ->action('Verify Here', route('twofactor.verify.index'))
                    ->line('The code will expire in 3 minutes')
                    ->line('If you have not tried to login, ignore this message.')
                    ->line('Thank you for using our website!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
