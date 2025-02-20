<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'project_id',
        'assigned_to',
        'created_by',
        'due_date'
    ];

    // Relation avec le projet
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relation avec l'utilisateur assigné
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Relation avec le créateur de la tâche
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
{
    return $this->hasMany(TaskComment::class);
}

// TaskComment.php
public function user()
{
    return $this->belongsTo(User::class);
}


public function documents()
{
    return $this->hasMany(Document::class); // Adjust if your document relationship is different.
}
}
