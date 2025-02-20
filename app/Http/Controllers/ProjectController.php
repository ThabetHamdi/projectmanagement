<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RoleMiddleware;

class ProjectController extends Controller
{
    /**
     * Affiche la liste des projets.
     */
    public function index()
    {
        return Inertia::render('Projects/Index', [
            'projects' => Project::with('manager')->get(),
        ]);
    }

    /**
     * Affiche le formulaire de création d’un projet.
     */
    public function create()
    {
        return Inertia::render('Projects/Create');
    }

    /**
     * Stocke un nouveau projet dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_date' => 'nullable|date',
            'ending_date' => 'nullable|date|after_or_equal:starting_date',
        ]);

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'en attente',
            'created_by' => auth()->id(), // Uniquement les managers
            'starting_date' => $request->starting_date,
            'ending_date' => $request->ending_date,
        ]);

        return redirect()->route('projects.index')->with('success', 'Projet créé avec succès.');
    }

    /**
     * Affiche les détails d’un projet.
     */
    public function show(Project $project)
    {
        return Inertia::render('Projects/Show', [
            'project' => $project->load('manager'),
        ]);
    }

    /**
     * Affiche le formulaire d’édition d’un projet.
     */
    public function edit(Project $project)
    {
        return Inertia::render('Projects/Edit', ['project' => $project]);
    }

    /**
     * Met à jour un projet.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en attente,en cours,terminé',
            'starting_date' => 'nullable|date',
            'ending_date' => 'nullable|date|after_or_equal:starting_date',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Projet mis à jour.');
    }

    /**
     * Supprime un projet.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Projet supprimé.');
    }

    /**
     * Affiche le tableau de bord du manager avec ses projets.
     */
    public function dashboard()
    {
        return Inertia::render('Manager/Dashboard', [
            'projects' => Project::where('created_by', auth()->id())->get(),
        ]);
    }
}
