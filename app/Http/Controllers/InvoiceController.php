<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function generateInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
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
                'message' => 'Unauthorized to generate invoice for this project.'
            ], 403);
        }

        if ($project->invoice) {
            return response()->json([
                'success' => false,
                'message' => 'An invoice for this project already exists.'
            ]);
        }

        if ($project->billing_type !== 'project') {
            return response()->json([
                'success' => false,
                'message' => 'Invoice generation for this billing type is handled differently.'
            ]);
        }

        $invoice = new Invoice();
        $invoice->recruiter_id = Auth::user()->id;
        $invoice->project_id = $project->id;
        $invoice->amount = $project->budget;
        $invoice->issued_at = Carbon::parse($request->start_date);
        $invoice->due_at = Carbon::parse($request->due_date);
        $invoice->status = 'pending';
        $invoice->save();

        session()->flash('success', 'Project invoice generated successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Invoice generated successfully!'
        ]);
    }

    public function generateInvoiceForTask(Request $request)
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

        $task = Task::with('project')->find($request->task_id);

        if (!$task || $task->project->recruiter_id !== Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to generate invoice for this task.'
            ], 403);
        }

        if ($task->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Only completed tasks can have invoices generated.'
            ]);
        }

        if ($task->invoice) {
            return response()->json([
                'success' => false,
                'message' => 'An invoice for this task already exists.'
            ]);
        }

        if (empty($task->price)) {
            return response()->json([
                'success' => false,
                'message' => 'Task must have a price to generate an invoice.'
            ]);
        }

        $invoice = new Invoice();
        $invoice->recruiter_id = Auth::user()->id;
        $invoice->project_id = $task->project_id; // Associate with project as well
        $invoice->task_id = $task->id;
        $invoice->amount = $task->price;
        $invoice->issued_at = Carbon::now();
        $invoice->due_at = Carbon::now()->addDays(7); // Example: Due in 7 days
        $invoice->status = 'pending';
        $invoice->save();

        session()->flash('success', 'Task invoice generated successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Invoice generated for task successfully!'
        ]);
    }

    public function requestRelease(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $invoice = Invoice::find($request->invoice_id);

        // Check if the current user is the talent associated with the project/task
        // This assumes project->talent_id is set when application is accepted
        if (
            ($invoice->project && $invoice->project->talent_id !== Auth::user()->id) &&
            ($invoice->task && $invoice->task->project->talent_id !== Auth::user()->id)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to request release for this invoice.'
            ], 403);
        }

        if ($invoice->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending invoices can be requested for release.'
            ]);
        }

        // Here you would typically trigger an action, e.g.,
        // 1. Send a notification to the recruiter to release payment.
        // 2. Potentially update invoice status to 'requested_release' or similar
        //    if you want a finer-grained status. For this example, we'll just acknowledge.

        // For now, let's just send a success message.
        // In a real application, you'd integrate with a payment gateway or notify the recruiter.

        // Example: Send notification to recruiter (you'd need to implement this)
        // Mail::to($invoice->recruiter->email)->send(new InvoiceReleaseRequestMail($invoice));

        // You might want to log this request or update a status field if you have a workflow
        // $invoice->status = 'release_requested';
        // $invoice->save();

        session()->flash('success', 'Invoice release request sent successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Invoice release request sent to recruiter!'
        ]);
    }
}
