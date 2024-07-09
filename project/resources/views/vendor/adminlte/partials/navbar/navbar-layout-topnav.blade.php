<nav class="main-header navbar {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }} {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">
    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('layouts.partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('layouts.partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    @if(count(config('panel.available_languages', [])) > 1)
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                {{ strtoupper(app()->getLocale()) }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @foreach(config('panel.available_languages') as $langLocale => $langName)
                    <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                @endforeach
            </div>
        </li>
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('layouts.partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('layouts.partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('layouts.partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('layouts.partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>
    @endif

</nav>
