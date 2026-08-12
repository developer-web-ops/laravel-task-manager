<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * List tasks with filtering, sorting, and pagination
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->tasks()->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search by title/description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter overdue
        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        // Filter due today
        if ($request->boolean('due_today')) {
            $query->dueToday();
        }

        // Sort
        $sortField     = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_dir', 'desc');
        $allowedSorts  = ['created_at', 'due_date', 'priority', 'title', 'status'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $tasks = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data'    => [
                'tasks'      => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page'    => $tasks->lastPage(),
                    'per_page'     => $tasks->perPage(),
                    'total'        => $tasks->total(),
                ],
                'stats'      => $request->user()->taskStats(),
            ],
        ]);
    }

    /**
     * Create a new task
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'status'      => 'nullable|in:todo,in_progress,done',
            'priority'    => 'nullable|in:low,medium,high,urgent',
            'due_date'    => 'nullable|date|after_or_equal:today',
            'reminder_at' => 'nullable|date|before_or_equal:due_date',
            'tags'        => 'nullable|array',
            'tags.*'      => 'string|max:50',
        ]);

        $validated['user_id']  = $request->user()->id;
        $validated['status']   = $validated['status'] ?? Task::STATUS_TODO;
        $validated['priority'] = $validated['priority'] ?? Task::PRIORITY_MEDIUM;

        $task = Task::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data'    => $task->fresh(),
        ], 201);
    }

    /**
     * Show a specific task
     */
    public function show(Request $request, Task $task): JsonResponse
    {
        Gate::authorize('view', $task);

        return response()->json([
            'success' => true,
            'data'    => $task,
        ]);
    }

    /**
     * Update a task
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string|max:5000',
            'status'      => 'sometimes|in:todo,in_progress,done',
            'priority'    => 'sometimes|in:low,medium,high,urgent',
            'due_date'    => 'sometimes|nullable|date',
            'reminder_at' => 'sometimes|nullable|date',
            'tags'        => 'sometimes|nullable|array',
            'tags.*'      => 'string|max:50',
        ]);

        // Auto-set completed_at when status changes to done
        if (isset($validated['status']) && $validated['status'] === Task::STATUS_DONE && $task->status !== Task::STATUS_DONE) {
            $validated['completed_at'] = Carbon::now();
        }

        // Reset completed_at if status reverts from done
        if (isset($validated['status']) && $validated['status'] !== Task::STATUS_DONE) {
            $validated['completed_at'] = null;
        }

        // Reset reminder_sent if reminder_at changes
        if (isset($validated['reminder_at'])) {
            $validated['reminder_sent'] = false;
        }

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data'    => $task->fresh(),
        ]);
    }

    /**
     * Delete a task (soft delete)
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }

    /**
     * Bulk update task statuses
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'task_ids'   => 'required|array',
            'task_ids.*' => 'integer|exists:tasks,id',
            'status'     => 'required|in:todo,in_progress,done',
        ]);

        $updated = $request->user()->tasks()
            ->whereIn('id', $validated['task_ids'])
            ->update([
                'status'       => $validated['status'],
                'completed_at' => $validated['status'] === Task::STATUS_DONE ? Carbon::now() : null,
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$updated} task(s) updated successfully",
            'data'    => ['updated_count' => $updated],
        ]);
    }

    /**
     * Bulk delete tasks
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'task_ids'   => 'required|array',
            'task_ids.*' => 'integer|exists:tasks,id',
        ]);

        $deleted = $request->user()->tasks()
            ->whereIn('id', $validated['task_ids'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deleted} task(s) deleted successfully",
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function stats(Request $request): JsonResponse
    {
        $user  = $request->user();
        $stats = $user->taskStats();

        // Weekly completion data (last 7 days)
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date            = Carbon::now()->subDays($i);
            $weeklyData[]    = [
                'date'  => $date->format('D'),
                'count' => $user->tasks()
                    ->where('status', Task::STATUS_DONE)
                    ->whereDate('completed_at', $date)
                    ->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => array_merge($stats, [
                'weekly_completions' => $weeklyData,
                'completion_rate'    => $stats['total'] > 0
                    ? round(($stats['done'] / $stats['total']) * 100, 1)
                    : 0,
            ]),
        ]);
    }
}
