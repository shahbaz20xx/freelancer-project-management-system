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
        $project = Project::where('id', $id)->with('client', 'projectType', 'projectCategory')->first();

        if ($project == null) {
            abort(404);
        }

        $applications = Application::where('project_id', $id)->with('recruiter')->get();

        $applied = 0;
        if (Auth::check()) {
            $currentUserId = Auth::user()->id;
            foreach ($applications as $application) {
                if ($application->recruiter_id === $currentUserId) {
                    $applied = 1;
                    $applications = $application;
                    break;
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
            'id' => 'required|exists:applications,id',
            'status' => 'required|in:accepted,rejected',
        ]);

        try {
            $application = Application::find($request->id);

            if ($application) {
                $application->status = $request->status;
                $application->save();

                return response()->json(['success' => true, 'message' => 'Application status updated successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Application not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the application.'], 500);
        }
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

        // You can't apply on your own project
        $client_id = $project->client_id;
        if ($client_id == Auth::user()->id) {
            $message = "You can not apply on your own project";
            Session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message,
            ]);
        }

        // User can't apply on a project twise
        $projectApplicationCount = Application::where([
            'recruiter_id' => Auth::user()->id,
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
        $application->recruiter_id = Auth::user()->id;
        $application->cover_letter = 'Applied on your project at ' . now();
        $application->status = 'pending';
        $application->save();

        // Send notification email to employer
        // $employer = User::where('id',$client_id)->first();
        // $mailData = [
        //     'employer' => $employer,
        //     'user' => Auth::user(),
        //     'project' => $project,
        // ];
        // Mail::to($employer->email)->send(new ProjectNotificationEmail($mailData));

        $message = "You have successfully applied.";
        Session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
