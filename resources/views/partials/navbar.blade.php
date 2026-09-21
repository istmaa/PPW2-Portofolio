<header class="site-header">
    <div class="nav-container">
        <a href="{{ route('home') }}" class="site-brand">Haydar Istma Ulhaq</a>

        <nav class="nav-menu">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('education') }}" class="nav-link {{ request()->routeIs('education') ? 'active' : '' }}">Education</a>
            <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects') ? 'active' : '' }}">Projects</a>
        </nav>
    </div>
</header>