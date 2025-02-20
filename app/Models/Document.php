<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    // Allow mass assignment for these attributes
    protected $fillable = [
        'name',
        'file_path',
        'task_id',
        'uploaded_by',
    ];

    // Define a relationship to the Task model (optional)
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
