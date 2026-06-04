<header class="main-header">
    <div class="header-left">
        <img src="{{ asset('img/facilities/UA-logo.png') }}" alt="University of Antique Logo" class="header-logo" />
        <div class="header-titles">
            <span class="ht-uni">University of Antique</span>
            <span class="ht-sys">Facility Management System</span>
        </div>
    </div>
    <div class="header-right">
        <nav class="nav-links">
            <a class="nav-link" href="#facilities">Facilities</a>
            <a class="nav-link" href="#calendar">Schedule</a>
            <a class="nav-link" href="#about">About</a>
        </nav>
        <div class="nav-actions">
            @auth
                @php
                    $user = auth()->user();
                    $dashRoute = $user->isAdmin()
                        ? 'admin.dashboard'
                        : ($user->isCollegeStaff()
                            ? 'college.dashboard'
                            : ($user->isOrgStaff()
                                ? 'org.dashboard'
                                : 'home'));
                @endphp
                <a href="{{ route($dashRoute) }}" class="btn-ghost">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-solid">Sign out</button>
                </form>
            @endauth
        </div>
    </div>
</header>


