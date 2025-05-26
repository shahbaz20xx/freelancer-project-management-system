<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('status', 1); // open projects

        // Search using keyword
        if (!empty($request->keyword)) {
            $projects = $projects->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keyword . '%');
                $query->orWhere('keywords', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->sort == '0') {

            $projects = $projects->orderBy('created_at', 'ASC');
        } else {

            $projects = $projects->orderBy('created_at', 'DESC');
        }

        $projects = $projects->paginate(9);


        return view('projects', [
            'projects' => $projects,
        ]);
    }

    public function projectDetail($id)
    {
        $project = Project::find($id);

        if ($project == null) {
            abort(404);
        }

        return view('projectDetail', [
            'project' => $project,
        ]);
    }
}
