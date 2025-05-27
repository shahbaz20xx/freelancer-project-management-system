@extends('layouts.app')


@section('content')

    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">My Project Applications</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    {{-- sidebar --}}
                    @include('account.sidebar')
                </div>
                <div class="col-lg-9">
                    {{-- Included message --}}
                    @include('message')

                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Projects Applied</h3>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Applied Date</th>
                                            <th scope="col">Applicants</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($projectApplications->isNotEmpty())
                                            @foreach ($projectApplications as $projectApplication)
                                                <tr class="active">
                                                    <td>
                                                        <div class="project-name fw-500">
                                                            {{ $projectApplication->project->title }}</div>
                                                        <div class="info1">
                                                            Project Nature:
                                                            {{ $projectApplication->project->projectType->name }}.
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($projectApplication->applied_date)->format('d M, Y') }}
                                                    </td>
                                                    <td>{{ $projectApplication->project->applications->count() }}
                                                        Applications</td>
                                                    <td>
                                                        <div class="project-status text-capitalize">
                                                            {{ $projectApplication->project->status }}</div>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-success btn-sm m-1"><a class="dropdown-item"
                                                            href="{{ route('projectDetail', $projectApplication->project_id) }}">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                            View</a></button>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">Job Applications not found</td>
                                            </tr>
                                        @endif

                                    </tbody>

                                </table>
                            </div>
                            <div>
                                {{ $projectApplications->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('customJs')
@endsection
