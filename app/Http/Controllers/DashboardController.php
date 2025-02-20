<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'terminé')->count();
        $pendingTasks = Task::where('status', 'à faire')->count();
        $inProgressTasks = Task::where('status', 'en cours')->count();

        // Project completion percentages
        $projects = Project::withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
            $query->where('status', 'terminé');
        }])->get();

        $projectCompletion = $projects->map(function ($project) {
            return [
                'name' => $project->name,
                'completion' => $project->tasks_count > 0 ? round(($project->completed_tasks_count / $project->tasks_count) * 100, 2) : 0,
            ];
        });

        // Task trends (last 7 days)
        $taskTrends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $taskTrends[$date] = Task::whereDate('created_at', $date)->count();
        }

        // Calendar events (tasks with due dates)
        $calendarEvents = Task::whereNotNull('due_date')
            ->select('title', 'due_date as start')
            ->get()
            ->map(function ($task) {
                return [
                    'title' => $task->title,
                    'start' => $task->start,
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalProjects' => $totalProjects,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'pendingTasks' => $pendingTasks,
                'inProgressTasks' => $inProgressTasks,
            ],
            'projectCompletion' => $projectCompletion,
            'taskTrends' => $taskTrends,
            'calendarEvents' => $calendarEvents,
        ]);
    }
}
