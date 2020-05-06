<?php

namespace App\Notifications;

use App\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMeetingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Meeting for nofity about.
     *
     * @var Meeting
     */
    protected $meeting;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
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
                    ->subject('New meeting is scheduled')
                    ->line('Meeting: '.$this->meeting->name)
                    ->line('Start time: '.$this->meeting->start)
                    ->line('End time: '.$this->meeting->end)
                    ->line('Thank you for using our application!');
    }
}
