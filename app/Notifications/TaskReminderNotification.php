<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Task $task) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dueText = $this->task->due_date
            ? 'Due: ' . $this->task->due_date->format('M d, Y \a\t h:i A')
            : 'No due date set';

        $priorityEmoji = match ($this->task->priority) {
            'urgent' => '🔴',
            'high'   => '🟠',
            'medium' => '🟡',
            'low'    => '🟢',
            default  => '⚪',
        };

        return (new MailMessage)
            ->subject("⏰ Reminder: {$this->task->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("This is a reminder for your task:")
            ->line("**{$priorityEmoji} {$this->task->title}**")
            ->when($this->task->description, function ($mail) {
                return $mail->line($this->task->description);
            })
            ->line($dueText)
            ->action('View Task', url("/tasks?task_id={$this->task->id}"))
            ->line('Stay productive! 🚀')
            ->salutation('— Task Manager Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id'   => $this->task->id,
            'task_title'=> $this->task->title,
            'due_date'  => $this->task->due_date?->toIso8601String(),
        ];
    }
}
