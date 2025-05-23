<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=s, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FPMS | Browse, Post Projects</title>
    <meta name="description"
        content="Browse and Post different types of Projects, Create Tasks to have a Clear Goal and request Invoices for your Work" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body data-instant-intensity="mousedown">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow py-3">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">FreelancePMS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-0 ms-sm-0 me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/') ? 'primary-color' : '' }}" aria-current="page"
                                href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('projects') ? 'primary-color' : '' }}" aria-current="page"
                                href="{{ route('projects') }}">Find Projects</a>
                        </li>
                    </ul>
                    @if (!Auth::check())
                        <a class="btn btn-outline-primary me-2" href="{{ route('account.login') }}"
                            type="submit">Login</a>
                    @else
                        @if (Auth::user()->role == 'admin')
                        @endif
                        <a class="btn btn-outline-primary me-2" href="{{ route('voyager.dashboard') }}"
                            type="submit">Admin</a>
                        
                        <a class="btn btn-outline-primary me-2" href="{{ route('account.login') }}"
                            type="submit">Account</a>
                    @endif
                    <a class="btn btn-primary" href="{{ route('account.login') }}" type="submit">Post a Job</a>
                </div>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer class="bg-dark py-3 bg-2">
        <div class="container">
            <p class="text-center text-white pt-3 fw-bold fs-6">© 2025 Threup Tech, all right reserved</p>
        </div>
    </footer>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
    </script>

    @yield('customJs')
</body>

</html>
