<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Inertia\Inertia;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $projects = Project::where('created_by', auth()->id())->with('teamMembers.user')->get();

        return Inertia::render('/Dashboard', [
            'projects' => $projects
        ]);
    }
}
