        <div class="card border-0 shadow mb-4 p-3">
            <div class="s-body text-center mt-3">

                @if (Auth::user()->avatar != '')
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="avatar"
                        class="rounded-circle img-fluid" style="width: 150px;">
                @else
                    <img src="{{ asset('storage/users/default.png') }}" alt="avatar" class="rounded-circle img-fluid"
                        style="width: 150px;">
                @endif

                <h5 class="mt-3 pb-0">{{ Auth::user()->name }}</h5>
                <p class="text-muted mb-1 fs-6">{{ Auth::user()->designation }}</p>
                <div class="d-flex justify-content-center mb-2">
                    <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button"
                        class="btn btn-primary">Change Profile Picture</button>
                </div>
            </div>
        </div>

        <div class="card account-nav border-0 shadow mb-4 mb-lg-0">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush ">
                    <li class="list-group-item d-flex justify-content-between p-3">
                        <a class="{{ Request::is('account/profile') ? 'light-green' : '' }}"
                            href="{{ route('account.profile') }}">Account Settings</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <a class="{{ Request::is('account/create-job') ? 'light-green' : '' }}"
                            href="{{ route('account.profile') }}">Post a Job</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <a class="{{ Request::is('account/my-jobs') ? 'light-green' : '' }}"
                            href="{{ route('account.profile') }}">My Jobs</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <a class="{{ Request::is('account/my-jobs-applications') ? 'light-green' : '' }}"
                            href="{{ route('account.profile') }}">Jobs Applied</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <a class="{{ Request::is('account/saved-jobs') ? 'light-green' : '' }}"
                            href="{{ route('account.profile') }}">Saved Jobs</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <a href="{{ route('account.logout') }}">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
