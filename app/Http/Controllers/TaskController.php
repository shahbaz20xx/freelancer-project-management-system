<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::find($request->project_id);

        if (!$project || $project->recruiter_id !== Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to add tasks to this project.'
            ], 403);
        }

        $task = new Task();
        $task->project_id = $request->project_id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->price = $request->price;
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task added successfully!',
            'task' => $task
        ]);
    }

    public function markComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $task = Task::find($request->task_id);

        if (!$task || $task->project->recruiter_id !== Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to mark this task complete.'
            ], 403);
        }

        if ($task->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Task is already completed.'
            ]);
        }

        $task->status = 'completed';
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task marked as complete!',
            'task' => $task
        ]);
    }
}
