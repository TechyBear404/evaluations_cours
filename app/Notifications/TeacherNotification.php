<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherNotification extends Notification
{
    use Queueable;

    private $message;
    private $fileContent;
    private $fileName;

    public function __construct($message, $fileContent = null, $fileName = null)
    {
        $this->message = $message;
        $this->fileContent = $fileContent;
        $this->fileName = $fileName;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Rapport d\'évaluation du cours')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line($this->message);

        if ($this->fileContent && $this->fileName) {
            $mailMessage->attachData($this->fileContent, $this->fileName, [
                'mime' => $this->getContentType($this->fileName),
            ]);
        }

        return $mailMessage;
    }

    private function getContentType($fileName): string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'application/pdf',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => 'application/octet-stream',
        };
    }
}
