<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Demo User ─────────────────────────────────────
        $demo = User::firstOrCreate(
            ['email' => 'demo@taskmanager.com'],
            [
                'name'     => 'Demo User',
                'password' => Hash::make('password'),
                'timezone' => 'Asia/Kolkata',
            ]
        );

        // ── Sample Tasks ───────────────────────────────────
        $now = Carbon::now();

        $tasks = [
            // Urgent / overdue
            [
                'title'       => 'Fix critical login bug on production',
                'description' => 'Users are unable to log in after the last deployment. Needs hotfix ASAP.',
                'status'      => 'in_progress',
                'priority'    => 'urgent',
                'due_date'    => $now->copy()->subHours(3),
                'tags'        => ['bug', 'production', 'hotfix'],
            ],
            [
                'title'       => 'Submit Q2 financial report',
                'description' => 'Compile revenue, expenses, and projections for Q2. Board review on Friday.',
                'status'      => 'todo',
                'priority'    => 'urgent',
                'due_date'    => $now->copy()->subDay(),
                'tags'        => ['finance', 'report'],
            ],
            // High priority — due soon
            [
                'title'       => 'Design new landing page mockup',
                'description' => 'Create Figma wireframes and high-fidelity mockups for the revamped homepage.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'due_date'    => $now->copy()->addDay(),
                'reminder_at' => $now->copy()->addHours(2),
                'tags'        => ['design', 'figma', 'frontend'],
            ],
            [
                'title'       => 'Code review for PR #142 — Auth refactor',
                'description' => 'Review the passport migration PR. Focus on token scopes and rate-limiting.',
                'status'      => 'todo',
                'priority'    => 'high',
                'due_date'    => $now->copy()->addHours(5),
                'tags'        => ['code-review', 'auth'],
            ],
            [
                'title'       => 'Write unit tests for TaskController',
                'description' => 'Cover index, store, update, destroy, and bulk endpoints. Aim for 90%+ coverage.',
                'status'      => 'todo',
                'priority'    => 'high',
                'due_date'    => $now->copy()->addDays(2),
                'tags'        => ['testing', 'backend', 'laravel'],
            ],
            // Due today
            [
                'title'       => 'Update API documentation in Postman',
                'description' => 'Sync the Postman collection with latest endpoint changes from last sprint.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'due_date'    => Carbon::today()->endOfDay(),
                'reminder_at' => $now->copy()->addHour(),
                'tags'        => ['docs', 'api'],
            ],
            [
                'title'       => 'Team standup prep — weekly goals',
                'description' => 'Prepare a summary of this week\'s achievements and blockers for the team standup.',
                'status'      => 'in_progress',
                'priority'    => 'medium',
                'due_date'    => Carbon::today()->endOfDay(),
                'tags'        => ['meetings', 'planning'],
            ],
            // Medium — upcoming
            [
                'title'       => 'Migrate legacy codebase to Laravel 13',
                'description' => 'Upgrade the old 9.x project. Update all deprecated package calls and test thoroughly.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'due_date'    => $now->copy()->addWeek(),
                'tags'        => ['migration', 'laravel', 'backend'],
            ],
            [
                'title'       => 'Set up CI/CD pipeline with GitHub Actions',
                'description' => 'Automate testing and deployment using GitHub Actions. Include Slack notifications.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'due_date'    => $now->copy()->addDays(10),
                'reminder_at' => $now->copy()->addDays(3),
                'tags'        => ['devops', 'ci-cd'],
            ],
            [
                'title'       => 'Conduct user interviews for feature v2',
                'description' => 'Schedule and run 5 user interviews to gather feedback for the next feature cycle.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'due_date'    => $now->copy()->addDays(5),
                'tags'        => ['ux', 'research'],
            ],
            [
                'title'       => 'Implement dark mode toggle',
                'description' => 'Add a dark/light theme toggle that persists the user preference in localStorage.',
                'status'      => 'in_progress',
                'priority'    => 'medium',
                'due_date'    => $now->copy()->addDays(4),
                'tags'        => ['frontend', 'ui', 'css'],
            ],
            [
                'title'       => 'Optimize database queries for task list',
                'description' => 'Profile N+1 queries on the task list page and add eager loading + indexes.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'due_date'    => $now->copy()->addDays(7),
                'tags'        => ['performance', 'database', 'laravel'],
            ],
            // Low priority
            [
                'title'       => 'Update README with setup instructions',
                'description' => 'Write clear installation, environment setup, and usage sections in README.md.',
                'status'      => 'todo',
                'priority'    => 'low',
                'due_date'    => $now->copy()->addDays(14),
                'tags'        => ['docs'],
            ],
            [
                'title'       => 'Explore Horizon for queue monitoring',
                'description' => 'Evaluate Laravel Horizon for real-time queue dashboards. Compare with Telescope.',
                'status'      => 'todo',
                'priority'    => 'low',
                'due_date'    => $now->copy()->addDays(20),
                'tags'        => ['laravel', 'queues', 'research'],
            ],
            [
                'title'       => 'Add Google Analytics to marketing site',
                'description' => 'Integrate GA4 tracking on the public landing page. Configure conversion events.',
                'status'      => 'todo',
                'priority'    => 'low',
                'due_date'    => $now->copy()->addDays(30),
                'tags'        => ['marketing', 'analytics'],
            ],
            // Done tasks
            [
                'title'       => 'Set up Laravel Passport authentication',
                'description' => 'Installed and configured Passport for OAuth2 API token management.',
                'status'      => 'done',
                'priority'    => 'high',
                'due_date'    => $now->copy()->subDays(5),
                'completed_at'=> $now->copy()->subDays(4),
                'tags'        => ['backend', 'auth', 'laravel'],
            ],
            [
                'title'       => 'Configure Mailtrap for email testing',
                'description' => 'Set up Mailtrap SMTP credentials and tested transactional email delivery.',
                'status'      => 'done',
                'priority'    => 'medium',
                'due_date'    => $now->copy()->subDays(6),
                'completed_at'=> $now->copy()->subDays(6),
                'tags'        => ['email', 'mailtrap'],
            ],
            [
                'title'       => 'Build task reminder notification system',
                'description' => 'Created TaskReminderNotification and Artisan command for scheduled email reminders.',
                'status'      => 'done',
                'priority'    => 'high',
                'due_date'    => $now->copy()->subDays(3),
                'completed_at'=> $now->copy()->subDays(2),
                'reminder_sent' => true,
                'tags'        => ['backend', 'notifications', 'queues'],
            ],
            [
                'title'       => 'Design task card UI components',
                'description' => 'Built priority-color-coded task cards with badges, tags, and quick-action buttons.',
                'status'      => 'done',
                'priority'    => 'medium',
                'due_date'    => $now->copy()->subWeek(),
                'completed_at'=> $now->copy()->subDays(6),
                'tags'        => ['frontend', 'design', 'bootstrap'],
            ],
            [
                'title'       => 'Define MVP feature scope',
                'description' => 'Aligned with stakeholders on core features: CRUD tasks, auth, email reminders, filters.',
                'status'      => 'done',
                'priority'    => 'high',
                'completed_at'=> $now->copy()->subDays(10),
                'tags'        => ['planning', 'product'],
            ],
        ];

        foreach ($tasks as $taskData) {
            $demo->tasks()->create(array_merge($taskData, [
                'reminder_sent' => $taskData['reminder_sent'] ?? false,
            ]));
        }

        $this->command->info('✅ Demo user seeded: demo@taskmanager.com / password');
        $this->command->info('✅ ' . count($tasks) . ' sample tasks created.');
    }
}
