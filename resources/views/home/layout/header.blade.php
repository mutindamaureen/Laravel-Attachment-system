    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-graduation-cap"></i> SAMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>

                    {{-- @if(Route::has('login'))
                        @auth
                            @if(Auth::user()->usertype == 'admin')
                                <li class="nav-item ms-2">
                                    <a href="{{ url('/admin/dashboard') }}" class="btn btn-dashboard">
                                        <i class="fas fa-dashboard"></i> Admin Dashboard
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('lecturer.dashboard') }}" class="btn btn-dashboard">
                                        <i class="fas fa-dashboard"></i> My Dashboard
                                    </a>

                                </li>

                            @else
                                <li>
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-dashboard">
                                        <i class="fas fa-dashboard"></i> My Dashboard
                                    </a>

                                </li>
                            @endif
                            <li class="nav-item ms-2">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-login">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item ms-2">
                                <a href="{{ route('login') }}" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            </li>
                            @if(Route::has('register'))
                                <li class="nav-item ms-2">
                                    <a href="{{ route('register') }}" class="btn btn-login">
                                        <i class="fas fa-user-plus"></i> Register
                                    </a>
                                </li>
                            @endif
                        @endauth
                    @endif --}}
                    @if(Route::has('login'))
                        @auth

                            @if(Auth::user()->usertype == 'admin')
                                {{-- Admin Dashboard --}}
                                <li class="nav-item ms-2">
                                    <a href="{{ url('/admin/dashboard') }}" class="btn btn-dashboard">
                                        <i class="fas fa-dashboard"></i> Admin Dashboard
                                    </a>
                                </li>

                            @elseif(Auth::user()->usertype == 'lecturer')
                                {{-- Lecturer Dashboard --}}
                                <li class="nav-item ms-2">
                                    <a href="{{ route('lecturer.dashboard') }}" class="btn btn-dashboard">
                                        <i class="fas fa-dashboard"></i> My Dashboard
                                    </a>
                                </li>

                            @elseif(Auth::user()->usertype == 'student')
                                {{-- Student Dashboard --}}
                                <li class="nav-item ms-2">
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-dashboard">
                                        <i class="fas fa-dashboard"></i> My Dashboard
                                    </a>
                                </li>
                            @elseif(Auth::user()->usertype == 'supervisor')
                                {{-- Student Dashboard --}}
                                <li class="nav-item ms-2">
                                    <a href="{{ route('supervisor.dashboard') }}" class="btn btn-dashboard">
                                        <i class="fas fa-dashboard"></i> My Dashboard
                                    </a>
                                </li>

                            @endif

                            {{-- Logout --}}
                            <li class="nav-item ms-2">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-login">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>

                        @else
                            {{-- Login Button --}}
                            <li class="nav-item ms-2">
                                <a href="{{ route('login') }}" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            </li>

                            {{-- Register Button --}}
                            @if(Route::has('register'))
                                <li class="nav-item ms-2">
                                    <a href="{{ route('register') }}" class="btn btn-login">
                                        <i class="fas fa-user-plus"></i> Register
                                    </a>
                                </li>
                            @endif
                        @endauth
                    @endif

                </ul>
            </div>
        </div>
    </nav>
