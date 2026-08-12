<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTaskReminders extends Command
{
    protected $signature   = 'tasks:send-reminders';
    protected $description = 'Send email reminders for tasks that are due';

    public function handle(): int
    {
        $tasks = Task::pendingReminders()
            ->with('user')
            ->get();

        if ($tasks->isEmpty()) {
            $this->info('No pending reminders found.');
            return Command::SUCCESS;
        }

        $sent = 0;
        foreach ($tasks as $task) {
            try {
                $task->user->notify(new TaskReminderNotification($task));
                $task->update(['reminder_sent' => true]);
                $sent++;
                $this->info("Reminder sent for task: [{$task->id}] {$task->title}");
            } catch (\Exception $e) {
                Log::error("Failed to send reminder for task {$task->id}: " . $e->getMessage());
                $this->error("Failed to send reminder for task: {$task->id}");
            }
        }

        $this->info("✅ Sent {$sent} reminder(s) out of {$tasks->count()} pending.");
        return Command::SUCCESS;
    }
}
