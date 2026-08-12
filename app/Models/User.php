<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function taskStats(): array
    {
        $tasks = $this->tasks();
        return [
            'total'       => $tasks->count(),
            'todo'        => $tasks->where('status', Task::STATUS_TODO)->count(),
            'in_progress' => $tasks->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'done'        => $tasks->where('status', Task::STATUS_DONE)->count(),
            'overdue'     => $tasks->overdue()->count(),
            'due_today'   => $tasks->dueToday()->count(),
        ];
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initials = implode('+', array_map(
            fn($word) => strtoupper(substr($word, 0, 1)),
            explode(' ', $this->name)
        ));
        return "https://ui-avatars.com/api/?name={$initials}&background=6366f1&color=fff&size=128";
    }
}