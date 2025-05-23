<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $topOpenProjects = Project::where('status', 'open')->orderByDesc('budget')->limit(10)->get();

        return view('home',[ 'featuredProjects' => $topOpenProjects ]);
    }
}
