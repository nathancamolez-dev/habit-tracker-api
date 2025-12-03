<?php

declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class WeeklyReport extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Collection $habits)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())->markdown('mail.weekly-report', [
            'map' => $this->getMap() ,
        ]);
    }

    public function getMap(): string
    {
        $habitNames = "| Day | " . $this->habits->groupBy('habit_name')->keys()
            ->map(fn ($name): string => "$name | ")->implode('');

        $splitter = "| Day | " . $this->habits->groupBy('habit_name')->keys()
            ->map(fn ($name): string => ":-------------: | ")->implode('');

        $days = "| Day | " . $this->habits->groupBy('log_date')
            ->map(function ($habit): string {
                $day  = $habit->first()->log_date->format('D j');
                $logs = $habit->map(fn ($item): string => ($item->completed ? '✅' : '❌') . ' |');

                return <<<HTML
                    | $day | $logs
                HTML;
            })->implode("\n");

        return  <<<HTML
    |         | $habitNames
    | :-----: | $splitter
    $days
    HTML;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
