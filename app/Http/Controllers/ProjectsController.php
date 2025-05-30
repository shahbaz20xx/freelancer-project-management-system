<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('status', 'open'); // open projects

        // Search using keyword
        if (!empty($request->keyword)) {
            $projects = $projects->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keyword . '%');
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
        $project = Project::where('id', $id)->with('recruiter', 'projectType', 'projectCategory', 'invoice', 'tasks.invoice')->first();

        if ($project == null) {
            abort(404);
        }

        $applied = 0;
        $applications = [];

        if (Auth::check()) {
            if (Auth::user()->id == $project->recruiter_id) {
                $applications = Application::where('project_id', $id)
                    ->with('talent')
                    ->get();
            } else {
                $applications = Application::where('talent_id', Auth::user()->id)
                    ->where('project_id', $id)
                    ->first();
                if ($applications) {
                    $applied = 1;
                }
            }
        }


        return view('projectDetail', [
            'project' => $project,
            'applications' => $applications,
            'applied' => $applied,
        ]);
    }

    public function updateApplicationStatus(Request $request)
    {
        $request->validate([
            'applicationId' => 'required|exists:applications,id',
            'projectId' => 'required|exists:projects,id',
            'status' => 'required|in:accepted,rejected',
        ]);

        if ($request->status == 'accepted') {
            $project = Project::where('id', $request->projectId)->with('applications')->first();
            $project->status = 'in_progress';
            
            foreach ($project->applications as $application) {
                if ($application->id == $request->applicationId) {
                    $project->talent_id = $application->talent_id;
                    $application->status = 'accepted';
                    $application->save();
                }
                else {
                    $application->status = 'rejected';
                    $application->save();
                }
            }
            $project->save();
        } else {
            $application = Application::find($request->applicationId);
            $application->status = $request->status;
            $application->save();
        }

        return response()->json(['success' => true, 'message' => 'Application status updated successfully.']);
    }

    public function applyProject(Request $request)
    {
        $id = $request->id;

        $project = Project::where('id', $id)->first();

        // If project not found
        if ($project == null) {
            $message = "Project does not exist";
            Session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        if ($project->status != 'open') {
            $message = "You can only apply on open projects";
            Session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        // You can't apply on your own project
        $recruiter_id = $project->recruiter_id;
        if ($recruiter_id == Auth::user()->id) {
            $message = "You can not apply on your own project";
            Session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        // User can't apply on a project twise
        $projectApplicationCount = Application::where([
            'talent_id' => Auth::user()->id,
            'project_id' => $id,
        ])->count();

        if ($projectApplicationCount > 0) {
            $message = "You already applied on this project.";
            Session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        $application = new Application();
        $application->project_id = $id;
        $application->talent_id = Auth::user()->id;
        $application->cover_letter = 'Applied on your project at ' . now();
        $application->status = 'pending';
        $application->save();

        $message = "You have successfully applied.";
        Session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
