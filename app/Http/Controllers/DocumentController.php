<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    // Upload a document for a task
    public function store(Request $request, Task $task)
    {
        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,docx|max:2048', // Added 'jpeg' and corrected 'jpg'
        ]);

        // Store the file in the 'documents' directory
        $path = $request->file('file')->store('documents');

        // Create a new document record
        Document::create([
            'name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $path,
            'task_id' => $task->id,
            'uploaded_by' => Auth::id(), // Use Auth facade instead of auth() helper
        ]);

        return redirect()->back()->with('success', 'Fichier ajouté avec succès.');
    }

    // Delete a document
    public function destroy(Document $document)
    {
        // Ensure the user is authorized to delete the document
        if ($document->uploaded_by !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer ce fichier.');
        }

        // Delete the file from storage
        Storage::delete($document->file_path);

        // Delete the document record
        $document->delete();

        return redirect()->back()->with('success', 'Fichier supprimé avec succès.');
    }
}
