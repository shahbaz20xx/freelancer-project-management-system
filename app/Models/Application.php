<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'talent_id', 'cover_letter', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }
}
