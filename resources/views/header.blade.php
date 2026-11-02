{{--
    TASK 2 + TASK 3 - Responsive navigation bar with role-based menu
    Guest (not logged in):  Mainpage, Database, Contact, Graph, Test, Login, Register
    Registered user:        + Messages, Logout
    Admin (role = 1):       + CRUD
--}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <i class="bi bi-geo-alt-fill"></i> Hungary Towns
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Mainpage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('database*') ? 'active' : '' }}" href="{{ url('/database') }}">Database</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('contact*') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('graph*') ? 'active' : '' }}" href="{{ url('/graph') }}">Graph</a>
                </li>

                {{-- Messages: only logged-in users --}}
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('messages*') ? 'active' : '' }}" href="{{ url('/messages') }}">Messages</a>
                    </li>

                    {{-- CRUD: only the admin (role = 1) --}}
                    @if (Auth::user()->role == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('crud*') ? 'active' : '' }}" href="{{ url('/crud') }}">CRUD</a>
                        </li>
                    @endif
                @endauth

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('calc*') ? 'active' : '' }}" href="{{ url('/calc') }}">Test</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('login') ? 'active' : '' }}" href="{{ url('/login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-lg-2 mt-2 mt-lg-0" href="{{ url('/register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            @if (Auth::user()->role == 1)
                                <span class="badge bg-warning text-dark ms-1">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                {{-- Logout needs POST (see php artisan route:list), so we use a form --}}
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
