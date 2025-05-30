@extends('layouts.app')

@section('content')

    <section class="section-3 py-5 bg-2 ">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-10 ">
                    <h2>Find projects</h2>
                </div>
                <div class="col-6 col-md-2">
                    <div class="align-end">
                        <select name="sort" id="sort" class="form-control">
                            <option value="1" {{ Request::get('sort') == '1' ? 'selected' : '' }}>Latest</option>
                            <option value="0" {{ Request::get('sort') == '0' ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-md-4 col-lg-3 sidebar mb-4">
                    <form action="" method="post" name="searchForm" id="searchForm">
                        <div class="card border-0 shadow p-4">
                            <div class="mb-4">
                                <h2>Keywords</h2>
                                <input type="text" value="{{ Request::get('keyword') }}" name="keyword" id="keyword"
                                    placeholder="Keywords" class="form-control">
                            </div>

                            {{-- <div class="mb-4">
                                <h2>Location</h2>
                                <input type="text" value="{{ Request::get('location') }}" name="location" id="location"
                                    placeholder="Location" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Category</h2>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                            <option {{ Request::get('category') == $category->id ? 'selected' : '' }}
                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div> --}}

                            {{-- <div class="mb-4">
                                <h2>project Type</h2>
                                @if ($projectTypes->isNotEmpty())
                                    @foreach ($projectTypes as $projectType)
                                        <div class="form-check mb-2">
                                            <input {{ in_array($projectType->id, $projectTypeArray) ? 'checked' : '' }}
                                                class="form-check-input " name="project_type" id="project-type-{{ $projectType->id }}"
                                                type="checkbox" value="{{ $projectType->id }}">
                                            <label class="form-check-label "
                                                for="project-type-{{ $projectType->id }}">{{ $projectType->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div> --}}

                            {{-- <div class="mb-4">
                                <h2>Experience</h2>
                                <select name="experience" id="experience" class="form-control">
                                    <option value="">Select Experience</option>
                                    <option value="1" {{ Request::get('experience') == 1 ? 'selected' : '' }}>1 Year
                                    </option>
                                    <option value="2" {{ Request::get('experience') == 2 ? 'selected' : '' }}>2
                                        Years</option>
                                    <option value="3" {{ Request::get('experience') == 3 ? 'selected' : '' }}>3
                                        Years</option>
                                    <option value="4" {{ Request::get('experience') == 4 ? 'selected' : '' }}>4
                                        Years</option>
                                    <option value="5" {{ Request::get('experience') == 5 ? 'selected' : '' }}>5
                                        Years</option>
                                    <option value="6" {{ Request::get('experience') == 6 ? 'selected' : '' }}>6
                                        Years</option>
                                    <option value="7" {{ Request::get('experience') == 7 ? 'selected' : '' }}>7
                                        Years</option>
                                    <option value="8" {{ Request::get('experience') == 8 ? 'selected' : '' }}>8
                                        Years</option>
                                    <option value="9" {{ Request::get('experience') == 9 ? 'selected' : '' }}>9
                                        Years</option>
                                    <option value="10" {{ Request::get('experience') == 10 ? 'selected' : '' }}>10
                                        Years</option>
                                    <option value="10_plus"
                                        {{ Request::get('experience') == '10_plus' ? 'selected' : '' }}>10+ Years
                                    </option>
                                </select>
                            </div> --}}

                            <button class="btn btn-primary" type="submit">Search</button>
                            <a href="{{ route('projects') }}" class="btn btn-secondary mt-3">Reset</a>
                        </div>
                    </form>
                </div>

                <div class="col-md-8 col-lg-9 ">
                    <div class="project_listing_area">
                        <div class="project_lists">
                            <div class="row">

                                @if ($projects->isNotEmpty())
                                    @foreach ($projects as $project)
                                        <div class="col-md-4">
                                            <div class="card border-0 p-3 shadow mb-4">
                                                <div class="card-body">
                                                    <h3 class="border-0 fs-5 pb-2 mb-0 text-truncate"><strong>{{ $project->title }}</strong></h3>
                                                    <p class="text-truncate">{{ $project->description }}
                                                    </p>
                                                    <div class="bg-light p-3 border">
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
                                                            <span class="ps-1"><strong>Experience</strong>: {{ $project->experience }}</span>
                                                        </p>
                                                    </div>

                                                    <div class="d-grid mt-3">
                                                        <a href="{{ route('projectDetail', $project->id) }}"
                                                            class="btn btn-primary btn-lg">Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-md-12">
                                        {{ $projects->withQueryString()->links() }}
                                    </div>
                                @else
                                    <div class="col-md-12">
                                        projects not found
                                    </div>
                                @endif

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
        $("#searchForm").submit(function(e) {
            e.preventDefault();

            var url = '{{ route('projects') }}?';

            var keywords = $("#keyword").val();
            // var location = $("#location").val();
            // var category = $("#category").val();
            // var experience = $("#experience").val();
            var sort = $("#sort").val();

            // first we will get checkbox value in array format
            // var checkedProjectTypes = $("input:checkbox[name='project_type']:checked").map(function() {
            //     return $(this).val();
            // }).get();

            // if keyword has a value
            if (keywords != "") {
                url += '&keyword=' + keywords;
            }

            // // if location field has a value
            // if (location != "") {
            //     url += '&location=' + location;
            // }

            // // if category field has a value
            // if (category != "") {
            //     url += '&category=' + category;
            // }

            // // if experience field has a value
            // if (experience != "") {
            //     url += '&experience=' + experience;
            // }

            // // if user has checked project types
            // if (checkedProjectTypes.length > 0) {
            //     url += '&projectType=' + checkedProjectTypes;
            // }

            // sort projects
            url += '&sort=' + sort;

            window.location.href = url;

        });

        $("#sort").change(function(e) {
            $("#searchForm").submit();

        });
    </script>
@endsection
