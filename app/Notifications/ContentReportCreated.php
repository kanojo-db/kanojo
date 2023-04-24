<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\ContentReport;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Series;
use App\Models\Studio;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContentReportCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ContentReport $contentReport)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $url = null;

        // Check the type of item that was reported and set the URL accordingly
        switch ($this->contentReport->reportable_type) {
            case Movie::class:
                $url = route('movies.reports.index', [
                    'movie' => $this->contentReport->reportable,
                ]);
                break;
            case Person::class:
                $url = route('models.reports.index', [
                    'model' => $this->contentReport->reportable,
                ]);
                break;
            case Studio::class:
                $url = route('studios.reports.index', [
                    'studio' => $this->contentReport->reportable,
                ]);
                break;
            case Series::class:
                $url = route('series.reports.index', [
                    'series' => $this->contentReport->reportable,
                ]);
                break;
        }

        return [
            'url' => $url,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = null;

        // Check the type of item that was reported and set the URL accordingly
        switch ($this->contentReport->reportable_type) {
            case Movie::class:
                $url = route('movies.reports.index', [
                    'movie' => $this->contentReport->reportable,
                ]);
                break;
            case Person::class:
                $url = route('models.reports.index', [
                    'model' => $this->contentReport->reportable,
                ]);
                break;
            case Studio::class:
                $url = route('studios.reports.index', [
                    'studio' => $this->contentReport->reportable,
                ]);
                break;
            case Series::class:
                $url = route('series.reports.index', [
                    'series' => $this->contentReport->reportable,
                ]);
                break;
        }

        return [
            'url' => $url,
        ];
    }
}
