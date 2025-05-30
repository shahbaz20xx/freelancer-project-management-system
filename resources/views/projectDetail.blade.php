@extends('layouts.app')


@section('content')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('projects') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i> &nbsp;Back to projects</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container project_details_area">
            <div class="row pb-5">
                <div class="col-md-8">

                    {{-- Session message --}}
                    @include('message')
                    {{-- Session message --}}

                    <div class="card shadow border-0">
                        <div class="project_details_header">
                            <div class="single_projects white-bg d-flex justify-content-between">
                                <div class="projects_left d-flex align-items-center">

                                    <div class="projects_conetent">
                                        <a href="#">
                                            <h4>{{ $project->title }}</h4>
                                        </a>
                                        <div class="links_locat p-3 align-items-center">
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                <span class="ps-1"><strong>Status</strong>: {{ $project->status }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                <span class="ps-1"><strong>Budget</strong>: {{ $project->budget }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                <span class="ps-1"><strong>Experience</strong>:
                                                    {{ $project->experience }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="jobs_right">
                                    <div class="apply_now {{ $count == 1 ? 'saved-job' : '' }}">
                                        <a class="heart_mark" href="javascript:void(0);"
                                            onclick="saveJob({{ $job->id }})"> <i class="fa fa-heart-o"
                                                aria-hidden="true"></i></a>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <div class="single_wrap">
                                <h4>Project description</h4>
                                <p>{!! nl2br($project->description) !!}</p>
                            </div>

                            @if (!empty($project->responsibility))
                                <div class="single_wrap">
                                    <h4>Responsibility</h4>
                                    {!! nl2br($project->responsibility) !!}
                                </div>
                            @endif

                            @if (!empty($project->qualifications))
                                <div class="single_wrap">
                                    <h4>Qualifications</h4>
                                    {!! nl2br($project->qualifications) !!}
                                </div>
                            @endif


                            @if (!empty($project->benefits))
                                <div class="single_wrap">
                                    <h4>Benefits</h4>
                                    <p>{!! nl2br($project->benefits) !!}</p>
                                </div>
                            @endif

                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">

                                {{-- @if (Auth::check())
                                    <a href="#" onclick="saveJob({{ $job->id }})"
                                        class="btn btn-secondary">Save</a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-secondary disabled">Login to Save</a>
                                @endif --}}

                                @if (Auth::check())
                                    <a href="javascript:void(0)" onclick="applyOnProject({{ $project->id }})"
                                        class="btn btn-primary">Apply</a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-primary disabled">Login to Apply</a>
                                @endif


                            </div>
                        </div>
                    </div>

                    @if (Auth::user())
                        @if (Auth::user()->id == $project->recruiter_id)

                            <div class="card shadow border-0 mt-4">
                                <div class="project_details_header">
                                    <div class="single_projects white-bg d-flex justify-content-between">
                                        <div class="projects_left d-flex align-items-center">

                                            <div class="projects_conetent">
                                                <h4>Applicants</h4>
                                            </div>
                                        </div>
                                        <div class="projects_right"></div>
                                    </div>
                                </div>
                                <div class="descript_wrap white-bg">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Cover Letter</th>
                                            <th>Application Status</th>
                                            <th>Applied Date</th>
                                        </tr>
                                        @if ($applications->isNotEmpty())
                                            @foreach ($applications as $application)
                                                <tr>
                                                    <td>{{ $application->talent->name }}</td>
                                                    <td>{{ $application->talent->email }}</td>
                                                    <td>{{ $application->cover_letter }}</td>
                                                    <td>
                                                        {{ $application->status }}
                                                        <div class="d-flex">
                                                            @if ($application->status === 'pending')
                                                                <button class="btn btn-success btn-sm m-1"
                                                                    onclick="updateApplicationStatus({{ $application->id }}, {{ $project->id }}, 'accepted')">
                                                                    Accept
                                                                </button>
                                                                <button class="btn btn-danger btn-sm m-1"
                                                                    onclick="updateApplicationStatus({{ $application->id }}, {{ $project->id }}, 'rejected')">
                                                                    Reject
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td> {{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">Applicants not found</td>
                                            </tr>
                                        @endif

                                    </table>
                                </div>

                            </div>

                            @if ($project->status != 'open' && $project->talent_id != null)
                                <div class="card shadow border-0 mt-4">
                                    <div class="project_details_header">
                                        <div class="single_projects white-bg d-flex justify-content-between">
                                            <div class="projects_left d-flex align-items-center">
                                                @if ($project->invoice == null)
                                                    <div class="projects_conetent">
                                                        <h4>Generate Invoice per {{ $project->billing_type }}</h4>
                                                    </div>
                                                @else
                                                    <h4>Project Invoice</h4>
                                                @endif
                                            </div>
                                            <div class="projects_right"></div>
                                        </div>
                                    </div>
                                    <div class="descript_wrap white-bg">
                                        @if ($project->billing_type == 'project')
                                            @if ($project->invoice == null)
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-center m-2">
                                                        <div class="">
                                                            <label for="startDate" class="mb-2">Start Date<span
                                                                    class="req">*</span></label>
                                                            <input type="date" id="startDate" name="startDate"
                                                                class="form-control"
                                                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                                            <p class="text-danger" id="startDateError"></p>
                                                        </div>
                                                        <div class="">
                                                            <label for="dueDate" class="mb-2">Due Date<span
                                                                    class="req">*</span></label>
                                                            <input type="date" id="dueDate" name="dueDate"
                                                                class="form-control">
                                                            <p class="text-danger" id="dueDateError"></p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center m-2">
                                                        <div class="">
                                                            <p>Total Amount: <strong>{{ $project->budget }}</strong></p>
                                                        </div>
                                                        <button class="btn btn-primary"
                                                            onclick="generateInvoice({{ $project->id }}, 'project')">Generate
                                                            Invoice</button>
                                                    </div>
                                                </div>
                                            @else
                                                @if ($project->invoice)
                                                    <p>Amount: <strong>{{ $project->invoice->amount }}</strong></p>
                                                    <p>Status: <strong>{{ $project->invoice->status }}</strong></p>
                                                    <p>Issued On:
                                                        <strong>{{ \Carbon\Carbon::parse($project->invoice->issued_at)->format('d M, Y') }}</strong>
                                                    </p>
                                                    <p>Due On:
                                                        <strong>{{ \Carbon\Carbon::parse($project->invoice->due_at)->format('d M, Y') }}</strong>
                                                    </p>
                                                @endif
                                            @endif
                                        @elseif ($project->billing_type == 'task')
                                            <div class="p-3">
                                                <h4>Tasks for this Project</h4>
                                                <button class="btn btn-primary mb-3" data-bs-toggle="modal"
                                                    data-bs-target="#addTaskModal">Add New Task</button>

                                                @if ($project->tasks->isNotEmpty())
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Title</th>
                                                                <th>Description</th>
                                                                <th>Status</th>
                                                                <th>Price</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($project->tasks as $task)
                                                                <tr>
                                                                    <td>{{ $task->title }}</td>
                                                                    <td>{{ Str::limit($task->description, 50) }}</td>
                                                                    <td>{{ $task->status }}</td>
                                                                    <td>{{ $task->price ?? 'N/A' }}</td>
                                                                    <td>
                                                                        @if ($task->status != 'completed')
                                                                            <button class="btn btn-sm btn-success"
                                                                                onclick="markTaskComplete({{ $task->id }})">Mark
                                                                                Complete</button>
                                                                        @endif
                                                                        @if ($task->status == 'completed' && !$task->invoice)
                                                                            <button class="btn btn-sm btn-info"
                                                                                onclick="generateInvoiceForTask({{ $task->id }})">Generate
                                                                                Invoice</button>
                                                                        @elseif($task->invoice)
                                                                            <span class="badge bg-success">Invoice
                                                                                Generated</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p>No tasks added for this project yet.</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @elseif ($applied == 1)
                            <div class="card shadow border-0 mt-4">
                                <div class="project_details_header">
                                    <div class="single_projects white-bg d-flex justify-content-between">
                                        <div class="projects_left d-flex align-items-center">

                                            <div class="projects_conetent">
                                                <h4>Your Application Status</h4>
                                            </div>
                                        </div>
                                        <div class="projects_right"></div>
                                    </div>
                                </div>
                                <div class="descript_wrap white-bg">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Cover Letter</th>
                                            <th>Application Status</th>
                                            <th>Applied Date</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $applications->talent->name }}</td>
                                            <td>{{ $applications->talent->email }}</td>
                                            <td>{{ $applications->cover_letter }}</td>
                                            <td><strong>{{ $applications->status }}</strong></td>
                                            <td> {{ \Carbon\Carbon::parse($applications->applied_date)->format('d M, Y') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="descript_wrap white-bg">
                                @if ($project->billing_type == 'project')
                                    <h4>Project Invoice</h4>
                                    @if ($project->invoice)
                                        <p>Amount: <strong>{{ $project->invoice->amount }}</strong></p>
                                        <p>Status: <strong>{{ $project->invoice->status }}</strong></p>
                                        <p>Issued On:
                                            <strong>{{ \Carbon\Carbon::parse($project->invoice->issued_at)->format('d M, Y') }}</strong>
                                        </p>
                                        <p>Due On:
                                            <strong>{{ \Carbon\Carbon::parse($project->invoice->due_at)->format('d M, Y') }}</strong>
                                        </p>
                                        @if ($project->invoice->status == 'pending')
                                            <button class="btn btn-success"
                                                onclick="requestInvoiceRelease({{ $project->invoice->id }})">Request
                                                Invoice Release</button>
                                        @endif
                                    @else
                                        <p>No invoice generated yet for this project.</p>
                                    @endif
                                @else
                                    <h4>Your Tasks and Invoices</h4>
                                    @if ($project->tasks->isNotEmpty())
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Status</th>
                                                    <th>Invoice Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($project->tasks as $task)
                                                    <tr>
                                                        <td>{{ $task->title }}</td>
                                                        <td>{{ $task->status }}</td>
                                                        <td>
                                                            @if ($task->invoice)
                                                                <span
                                                                    class="badge bg-{{ $task->invoice->status == 'paid' ? 'success' : ($task->invoice->status == 'overdue' ? 'danger' : 'warning') }}">
                                                                    {{ ucfirst($task->invoice->status) }}
                                                                </span>
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($task->status == 'completed' && $task->invoice && $task->invoice->status == 'pending')
                                                                <button class="btn btn-sm btn-success"
                                                                    onclick="requestInvoiceRelease({{ $task->invoice->id }})">Request
                                                                    Invoice Release</button>
                                                            @elseif($task->invoice && $task->invoice->status == 'paid')
                                                                <span class="badge bg-success">Paid</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>No tasks assigned for this project yet.</p>
                                    @endif
                                @endif
                            </div>
                        @endif
                    @endif

                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="project_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Project Summery</h3>
                            </div>
                            <div class="project_content pt-3">
                                <ul>
                                    <li>Published on:
                                        <span>{{ \Carbon\Carbon::parse($project->created_at)->format('d M, Y') }}</span>
                                    </li>

                                    @if (!empty($project->budget))
                                        <li>Budget: <span>{{ $project->budget }}</span></li>
                                    @endif

                                    <li>Project Nature: <span> {{ $project->projectType->name }}</span></li>
                                    <li>Project Category: <span> {{ $project->projectCategory->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow border-0 my-4">
                        <div class="project_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Recruiter Details</h3>
                            </div>
                            <div class="project_content pt-3">
                                <ul>
                                    <li>Name: <span>{{ $project->recruiter->name }}</span></li>

                                    <li>Email: <span>{{ $project->recruiter->email }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTaskForm">
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="taskTitle" name="title" required>
                            <p class="text-danger" id="taskTitleError"></p>
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="taskDescription" name="description" rows="3" required></textarea>
                            <p class="text-danger" id="taskDescriptionError"></p>
                        </div>
                        <div class="mb-3">
                            <label for="taskPrice" class="form-label">Price (Optional)</label>
                            <input type="number" step="0.01" class="form-control" id="taskPrice" name="price">
                            <p class="text-danger" id="taskPriceError"></p>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('customJs')
    <script type="text/javascript">
        function applyOnProject(id) {

            if (confirm('Are you sure you want to apply on this job?')) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('applyProject') }}',
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        window.location.href = "{{ url()->current() }}";
                    }
                });
            }
        }

        function updateApplicationStatus(applicationId, projectId, newStatus) {
            if (confirm('Are you sure you want to ' + newStatus.toLowerCase() + ' this application?')) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('updateApplicationStatus') }}',
                    data: {
                        applicationId: applicationId,
                        projectId: projectId,
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            window.location.href = "{{ url()->current() }}";
                        } else {
                            console.error('Failed to update application status:', response.message);
                            alert('Error: ' + response
                                .message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText);
                        alert(
                            'An error occurred while updating the application. Please try again.'
                        );
                    }
                });
            }
        }

        function generateInvoice(id, type) {
            let startDate = $('#startDate').val();
            let dueDate = $('#dueDate').val();

            // Clear previous errors
            $('#startDateError').text('');
            $('#dueDateError').text('');

            if (type === 'project') {
                if (!startDate) {
                    $('#startDateError').text('Start date is required.');
                    return;
                }
                if (!dueDate) {
                    $('#dueDateError').text('Due date is required.');
                    return;
                }

                if (new Date(startDate) > new Date(dueDate)) {
                    $('#dueDateError').text('Due date cannot be before start date.');
                    return;
                }
            }


            if (confirm('Are you sure you want to Generate ' + type + ' Based Invoice?')) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('generateInvoice') }}', // Changed to a new route for invoice generation
                    data: {
                        project_id: id,
                        start_date: startDate,
                        due_date: dueDate,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            window.location.href = "{{ url()->current() }}";
                        } else {
                            console.error('Failed to generate invoice:', response.message);
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText);
                        alert('An error occurred while generating the invoice. Please try again.');
                    }
                });
            }
        }

        $('#addTaskForm').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            let formData = form.serialize();

            // Clear previous errors
            $('#taskTitleError').text('');
            $('#taskDescriptionError').text('');
            $('#taskPriceError').text('');

            $.ajax({
                url: '{{ route('tasks.store') }}',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#addTaskModal').modal('hide');
                        window.location.reload(); // Reload to see the new task
                    } else {
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                $('#task' + key.charAt(0).toUpperCase() + key.slice(1) +
                                    'Error').text(value);
                            });
                        }
                        console.error('Failed to add task:', response.message);
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error, xhr.responseText);
                    alert('An error occurred while adding the task. Please try again.');
                }
            });
        });

        function markTaskComplete(taskId) {
            if (confirm('Are you sure you want to mark this task as complete?')) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('tasks.markComplete') }}',
                    data: {
                        task_id: taskId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            console.error('Failed to mark task complete:', response.message);
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText);
                        alert('An error occurred while marking the task complete. Please try again.');
                    }
                });
            }
        }

        function generateInvoiceForTask(taskId) {
            if (confirm('Are you sure you want to generate an invoice for this task?')) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('invoices.generateForTask') }}',
                    data: {
                        task_id: taskId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            console.error('Failed to generate invoice for task:', response.message);
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText);
                        alert('An error occurred while generating the invoice. Please try again.');
                    }
                });
            }
        }

        function requestInvoiceRelease(invoiceId) {
            if (confirm('Are you sure you want to request the release of this invoice?')) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('invoices.requestRelease') }}',
                    data: {
                        invoice_id: invoiceId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            window.location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText);
                        alert('An error occurred while requesting invoice release. Please try again.');
                    }
                });
            }
        }
    </script>
@endsection
