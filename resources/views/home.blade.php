@extends('layouts.app')

@section('content')

<section class="section-0 lazy d-flex bg-image-style dark align-items-center " class="" data-bg="{{ asset('assets/images/banner5.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-12 col-xl-8">
                <h1>Find your dream projects</h1>
                <p>Thounsands of projects available.</p>
                <div class="banner-btn mt-5"><a href="{{ route('projects') }}" class="btn btn-primary mb-4 mb-sm-0">Explore Now</a></div>
            </div>
        </div>
    </div>
</section>

<section class="section-1 py-5 ">
    <div class="container">
        <form action="{{ route('projects') }}" method="get">
        <div class="card border-0 shadow p-5">
            <div class="row">
                <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                    <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Keywords">
                </div>
                {{-- <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                    <input type="text" class="form-control" name="location" id="location" placeholder="Location">
                </div> --}}
                {{-- <div class="col-md-3 mb-3 mb-sm-3 mb-lg-0">
                    <select name="category" id="category" class="form-control">
                        <option value="">Select a Category</option>
                        @if($allCategories->isNotEmpty())
                           @foreach ($allCategories as $category)
                           <option value="{{ $category->id }}">{{ $category->name }}</option>
                           @endforeach
                       @endif
                    </select>
                </div> --}}

                <div class=" col-md-3 mb-xs-3 mb-sm-3 mb-lg-0">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
    </div>
</section>

<section class="section-3  py-5">
    <div class="container">
        <h2>Featured Projects</h2>
        <div class="row pt-5">
            <div class="job_listing_area">
                <div class="job_lists">
                    <div class="row">

                    @if ($featuredProjects->isNotEmpty())
                        @foreach ($featuredProjects as $featuredProject)

                        <div class="col-md-4">
                            <div class="card border-0 p-3 shadow mb-4">
                                <div class="card-body">
                                    <h3 class="border-0 fs-5 pb-2 mb-0 text-truncate">{{ $featuredProject->title }}</h3>

                                    <p>{{ Str::words( strip_tags($featuredProject->description), 5) }}</p>
                                    <div class="bg-light p-3 border">
                                        <p class="mb-0">
                                            <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                            <span class="ps-1">{{ $featuredProject->status }}</span>
                                        </p>
                                        {{-- <p class="mb-0">
                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                            <span class="ps-1">{{ $featuredProject->jobType->name }}</span>
                                        </p> --}}
                                        @if (!is_null($featuredProject->budget))
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                <span class="ps-1">{{ $featuredProject->budget }}</span>
                                            </p>
                                        @endif

                                    </div>

                                    <div class="d-grid mt-3">
                                        <a href="{{ route('projectDetail', $featuredProject->id) }}" class="btn btn-primary btn-lg">Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection