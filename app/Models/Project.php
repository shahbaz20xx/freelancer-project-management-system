<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'recruiter_id', 'talent_id', 'project_category_id', 'project_type_id', 'status', 'budget', 'billing_type'];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function invoice() // Corrected method name
    {
        return $this->hasOne(Invoice::class); // Assuming one invoice per project
    }

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function projectCategory()
    {
        return $this->belongsTo(ProjectCategory::class);
    }
}
