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
                                                                    onclick="updateApplicationStatus({{ $application->id }}, 'accepted')">
                                                                    Accept
                                                                </button>
                                                                <button class="btn btn-danger btn-sm m-1"
                                                                    onclick="updateApplicationStatus({{ $application->id }}, 'rejected')">
                                                                    Reject
                                                                </button>
                                                            @else
                                                                <span
                                                                    class="badge {{ $application->status === 'accepted' ? 'badge-success' : ($application->status === 'rejected' ? 'badge-danger' : 'badge-info') }}">
                                                                    {{ $application->status }}
                                                                </span>
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
                                <h3>Client Details</h3>
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

        function updateApplicationStatus(applicationId, newStatus) {
            // Confirmation dialog before sending the AJAX request
            if (confirm('Are you sure you want to ' + newStatus.toLowerCase() + ' this application?')) {
                $.ajax({
                    type: "POST",
                    // Use a named route for better maintainability.
                    // You'll need to define 'updateApplicationStatus' route in your web.php
                    url: '{{ route('updateApplicationStatus') }}',
                    data: {
                        id: applicationId,
                        status: newStatus,
                        _token: '{{ csrf_token() }}' // Laravel CSRF token for security
                    },
                    dataType: "json",
                    success: function(response) {
                        // Check the response for success message
                        if (response.success) {
                            // Reload the current page to reflect the status change
                            window.location.href = "{{ url()->current() }}";
                        } else {
                            // Log or display an error message if the update failed on the server
                            console.error('Failed to update application status:', response.message);
                            alert('Error: ' + response
                                .message); // Use alert for error, but still consider custom modal
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors (e.g., network issues, server errors)
                        console.error('AJAX Error:', status, error, xhr.responseText);
                        alert(
                            'An error occurred while updating the application. Please try again.'
                        ); // Custom modal better
                    }
                });
            }
        }
    </script>
@endsection
