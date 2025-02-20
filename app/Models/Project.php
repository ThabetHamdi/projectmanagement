<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status', 'created_by', 'starting_date', 'ending_date'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function teamMembers()
{
    return $this->hasMany(ProjectTeam::class);
}
public function tasks()
{
    return $this->hasMany(Task::class);
}

}
