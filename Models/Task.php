<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'reminder_at',
        'reminder_sent',
        'tags',
        'completed_at',
    ];

    protected $casts = [
        'due_date'      => 'datetime',
        'reminder_at'   => 'datetime',
        'completed_at'  => 'datetime',
        'reminder_sent' => 'boolean',
        'tags'          => 'array',
    ];

    // Status constants
    const STATUS_TODO        = 'todo';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DONE        = 'done';

    // Priority constants
    const PRIORITY_LOW    = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH   = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Relationship: Task belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Tasks due today
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', Carbon::today());
    }

    /**
     * Scope: Overdue tasks
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::now())
                     ->where('status', '!=', self::STATUS_DONE);
    }

    /**
     * Scope: Pending reminders
     */
    public function scopePendingReminders($query)
    {
        return $query->where('reminder_sent', false)
                     ->whereNotNull('reminder_at')
                     ->where('reminder_at', '<=', Carbon::now())
                     ->where('status', '!=', self::STATUS_DONE);
    }

    /**
     * Accessor: Is task overdue?
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== self::STATUS_DONE;
    }

    /**
     * Accessor: Priority badge color
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW    => 'success',
            self::PRIORITY_MEDIUM => 'info',
            self::PRIORITY_HIGH   => 'warning',
            self::PRIORITY_URGENT => 'danger',
            default               => 'secondary',
        };
    }

    /**
     * Accessor: Status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_TODO        => 'secondary',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_DONE        => 'success',
            default                  => 'secondary',
        };
    }

    /**
     * Mark task as complete
     */
    public function markComplete(): void
    {
        $this->update([
            'status'       => self::STATUS_DONE,
            'completed_at' => Carbon::now(),
        ]);
    }
}
