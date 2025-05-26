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
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p> <i class="fa fa-map-marker"></i> {{ $project->location }}</p>
                                            </div>
                                            {{-- <div class="location">
                                                <p> <i class="fa fa-clock-o"></i> {{ $project->projectType->name }}</p>
                                            </div> --}}
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
                                    <a href="javascript:void(0)" onclick="applyProject({{ $project->id }})"
                                        class="btn btn-primary">Apply</a>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-primary disabled">Login to Apply</a>
                                @endif


                            </div>
                        </div>
                    </div>

                    @if (Auth::user())
                        @if (Auth::user()->id == $project->user_id)

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
                                            <th>Mobile</th>
                                            <th>Applied Date</th>
                                        </tr>
                                        @if ($applications->isNotEmpty())
                                            @foreach ($applications as $application)
                                                <tr>
                                                    <td>{{ $application->user->name }}</td>
                                                    <td>{{ $application->user->email }}</td>
                                                    <td>{{ $application->user->mobile }}</td>
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
                                    <li>Vacancy: <span>{{ $project->vacancy }} Position</span></li>

                                    @if (!empty($project->salary))
                                        <li>Salary: <span>{{ $project->salary }}</span></li>
                                    @endif

                                    <li>Location: <span>{{ $project->location }}</span></li>
                                    {{-- <li>project Nature: <span> {{ $project->jobType->name }}</span></li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow border-0 my-4">
                        <div class="project_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Company Details</h3>
                            </div>
                            <div class="project_content pt-3">
                                <ul>
                                    <li>Name: <span>{{ $project->company_name }}</span></li>

                                    @if (!empty($project->company_location))
                                        <li>Locaion: <span>{{ $project->company_location }}</span></li>
                                    @endif

                                    @if (!empty($project->company_website))
                                        <li>Webite: <span><a href="{{ $project->company_website }}"
                                                    target="_blank">{{ $project->company_website }}</a></span></li>
                                    @endif

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
    <script type="text/javascript"></script>
@endsection
