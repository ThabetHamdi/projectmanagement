<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;

class DeveloperDashboardController extends Controller
{
    /**
     * Display a customized dashboard for developers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Retrieve tasks assigned to the current developer.
        $tasks = Task::where('assigned_to', $user->id)->get();

        // Retrieve projects related to those tasks.
        $projectIds = $tasks->pluck('project_id')->unique();
        $projects = Project::whereIn('id', $projectIds)->get();

        // Retrieve comments on the tasks assigned to the developer.
        $taskIds = $tasks->pluck('id')->unique();
        $comments = TaskComment::whereIn('task_id', $taskIds)->get();

        return Inertia::render('DeveloperDashboard', [
            'projects' => $projects,
            'tasks'    => $tasks,
            'comments' => $comments,
        ]);
    }
}
