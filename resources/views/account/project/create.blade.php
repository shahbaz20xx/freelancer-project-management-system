@extends('layouts.app')

@section('content')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Post a Project</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('account.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('message')
                    <form action="" method="post" name="createProjectForm" id="createProjectForm">
                        @csrf
                        <div class="card border-0 shadow mb-4">
                            <div class="card-body card-form p-4">
                                <h3 class="fs-4 mb-1">Project Details</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Title<span class="req">*</span></label>
                                        <input type="text" placeholder="Project Title" id="title" name="title"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Project Category<span
                                                class="req">*</span></label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select a Category</option>
                                            @if ($project_categories->isNotEmpty())
                                                @foreach ($project_categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Project Type<span
                                                class="req">*</span></label>
                                        <select name="projectType" id="projectType" class="form-select">
                                            <option value="">Select Project Type</option>
                                            @if ($project_types->isNotEmpty())
                                                @foreach ($project_types as $project_type)
                                                    <option value="{{ $project_type->id }}">{{ $project_type->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Budget</label>
                                        <input type="text" placeholder="Salary" id="budget" name="budget"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Experience<span class="req">*</span></label>
                                        <select name="experience" id="experience" class="form-control">
                                            <option value="">Select Experience</option>
                                            <option value="1">1 Years</option>
                                            <option value="2">2 Years</option>
                                            <option value="3">3 Years</option>
                                            <option value="4">4 Years</option>
                                            <option value="5">5 Years</option>
                                            <option value="6">6 Years</option>
                                            <option value="7">7 Years</option>
                                            <option value="8">8 Years</option>
                                            <option value="9">9 Years</option>
                                            <option value="10">10 Years</option>
                                            <option value="10_plus">10+ Years</option>
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Billing Type<span
                                                class="req">*</span></label>
                                        <select name="billing_type" id="billing_type" class="form-control">
                                            <option value="">Select a Billing Type</option>
                                            <option value="project">Project Based</option>
                                            <option value="task">Task Based</option>
                                        </select>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="" class="mb-2">Description<span class="req">*</span></label>
                                    <textarea class="textarea" name="description" id="description" cols="5" rows="5" placeholder="Description"></textarea>
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Responsibility</label>
                                    <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5"
                                        placeholder="Responsibility"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Qualifications</label>
                                    <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5"
                                        placeholder="Qualifications"></textarea>
                                </div>
                            </div>
                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-primary">Upload Project</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

@endsection

@section('customJs')
    <script type="text/javascript">
        $("#createProjectForm").submit(function(e) {
            e.preventDefault();

            $("button[type='submit']").prop('disabled', true);

            $.ajax({
                type: "post",
                url: "{{ route('account.uploadProject') }}",
                data: $("#createProjectForm").serializeArray(),
                dataType: "json",
                success: function(response) {

                    $("button[type='submit']").prop('disabled', false);

                    if (response.status == true) {

                        $("#title").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                        $("#category").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                        $("#projectType").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                        $("#description").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                        $("#experience").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                        $("#billing_type").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                        window.location.href = "{{ route('account.myProjects') }}";

                    } else {

                        var errors = response.errors;
                        // For name
                        if (errors.title) {
                            $("#title").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.title);
                        } else {
                            $("#title").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }

                        // For category
                        if (errors.category) {
                            $("#category").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.category);
                        } else {
                            $("#category").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }

                        // For projectType
                        if (errors.projectType) {
                            $("#projectType").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.projectType);
                        } else {
                            $("#projectType").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }

                        // For description
                        if (errors.description) {
                            $("#description").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.description);
                        } else {
                            $("#description").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }

                        // For Experience
                        if (errors.experience) {
                            $("#experience").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.experience);
                        } else {
                            $("#experience").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }

                        // For Billing Type
                        if (errors.billing_type) {
                            $("#billing_type").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.billing_type);
                        } else {
                            $("#billing_type").removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }
                    }
                }
            });

        });
    </script>
@endsection
