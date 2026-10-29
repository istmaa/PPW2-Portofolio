<header class="site-header">
    <div class="nav-container">
        <a href="{{ route('home') }}" class="site-brand">Haydar Istma Ulhaq</a>

        <nav class="nav-menu">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('education') }}" class="nav-link {{ request()->routeIs('education') ? 'active' : '' }}">Education</a>
            <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">Projects</a>
            @auth
                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    {{ Auth::user()->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
            @endauth
        </nav>
    </div>
</header>