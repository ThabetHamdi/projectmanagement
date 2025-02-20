<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Inertia\Inertia;
use App\Notifications\TaskUpdatedNotification;
use Carbon\Carbon;
use TCPDF;
class TaskController extends Controller
{
    // Afficher les tâches d'un projet
    public function index(Project $project)
    {
        // Charger les tâches avec les utilisateurs assignés
        $project->load('tasks.assignedUser');
        // Récupérer tous les utilisateurs pour l'assignation
        $users = User::all();

        return Inertia::render('Tasks/Index', [
            'project' => $project,
            'tasks' => $project->tasks,
            'users' => $users
        ]);
    }

    //get all tasks for by project using sql query
    public function getTasksByProject(Project $project)
    {
        $tasks = Task::where('project_id', $project->id)->get();
        return Inertia::render('Tasks/Index', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    // Afficher une tâche spécifique
    public function show(Task $task)
    {
        return Inertia::render('Tasks/Show', [
            'task' => $task->load('comments.user', 'assignedUser', 'project'),
        ]);
    }

    // Créer une nouvelle tâche
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:à faire,en cours,terminé',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'project_id' => $project->id,
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
            'due_date' => $request->due_date,
        ]);

        // Notify the assigned user if applicable
        if ($task->assigned_to) {
            $user = User::find($task->assigned_to);
            $user->notify(new TaskUpdatedNotification($task, 'created'));
        }

        return redirect()->back()->with('success', 'Tâche ajoutée.');
    }

    // Mettre à jour une tâche existante
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:à faire,en cours,terminé',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->all());

        // Notify the assigned user if applicable
        if ($task->assigned_to) {
            $user = User::find($task->assigned_to);
            $user->notify(new TaskUpdatedNotification($task, 'updated'));
        }

        return redirect()->back()->with('success', 'Tâche mise à jour.');
    }

    // Supprimer une tâche
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Tâche supprimée.');
    }

    // Vérifier si une tâche est en retard
    public function checkIfTaskIsPastDue(Task $task)
    {
        if ($task->due_date && Carbon::now()->gt($task->due_date)) {
            return true; // Task is past due
        }
        return false; // Task is not past due
    }




    public function edit(Task $task)
{
    return Inertia::render('Tasks/Edit', [
        'taskData' => $task,
    ]);
}


// Exporter la liste des tâches en PDF



public function exportPdf(Project $project)
{
    $tasks = $project->tasks()->with('assignedUser')->get();

    // Create new PDF instance
    $pdf = new TCPDF();

    // Set document information
    $pdf->SetCreator('Laravel');
    $pdf->SetAuthor('Your App');
    $pdf->SetTitle('Liste des Tâches - ' . $project->name);
    $pdf->SetSubject('Liste des Tâches');

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 10);

    // Add a page
    $pdf->AddPage();

    // Set title
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Liste des Tâches pour le Projet: ' . $project->name, 0, 1, 'C');

    // Set table headers
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(60, 10, 'Titre', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Statut', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Assigné à', 1, 1, 'C');

    // Set table content
    $pdf->SetFont('helvetica', '', 11);
    foreach ($tasks as $task) {
        $pdf->Cell(60, 10, $task->title, 1, 0, 'L');
        $pdf->Cell(40, 10, $task->status, 1, 0, 'C');
        $pdf->Cell(60, 10, $task->assignedUser->name ?? 'Non assigné', 1, 1, 'C');
    }

    // Output the PDF
    return response($pdf->Output('tasks-' . $project->name . '.pdf', 'I'))
        ->header('Content-Type', 'application/pdf');
}

}
