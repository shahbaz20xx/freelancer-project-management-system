<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'client_id', 'recruiter_id', 'status', 'budget', 'billing_type'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
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
}
