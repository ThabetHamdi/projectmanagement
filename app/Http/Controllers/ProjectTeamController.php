<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectTeam;
use App\Models\Project;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class ProjectTeamController extends Controller
{
    public function index($projectId)
    {
        $project = Project::with('teamMembers.user')->findOrFail($projectId);
        $users = User::whereNotIn('id', $project->teamMembers->pluck('user_id'))->get();

        return Inertia::render('Projects/Team', [
            'project' => $project,
            'users' => $users,
        ]);
    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:manager,developer',
        ]);

        ProjectTeam::create([
            'project_id' => $projectId,
            'user_id' => $request->user_id,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Membre ajouté.');
    }

    public function destroy($projectId, $userId)
    {
        ProjectTeam::where('project_id', $projectId)->where('user_id', $userId)->delete();

        return redirect()->back()->with('success', 'Membre supprimé.');
    }
}
