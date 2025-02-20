<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskComment;
use App\Models\Task;
use Inertia\Inertia;

class TaskCommentController extends Controller
{
    // Fetch comments for a specific task
    public function index(Task $task)
    {
        // Load the task with its comments and the user who made each comment
        $task->load('comments.user');

        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    // Store a new comment for a task
    public function store(Request $request, Task $task)
    {
        // Validate the comment input
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Create the comment
        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Commentaire ajouté.');
    }

    // Delete a comment
    public function destroy(TaskComment $comment)
    {
        // Ensure the authenticated user is the owner of the comment
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }

        // Delete the comment
        $comment->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Commentaire supprimé.');
    }
}
