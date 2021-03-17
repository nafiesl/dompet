<div class="dropdown">
    <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
        <span class="avatar avatar-placeholder"></span>
        <span class="ml-2 d-none d-lg-block">
            <span class="text-default">{{ auth()->user()->name }}</span>
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a class="dropdown-item" href="{{ route('profile.show') }}">
            <i class="dropdown-icon fe fe-user"></i> {{ __('auth.profile') }}
        </a>
        <a class="dropdown-item" href="{{ route('password.change') }}">
            <i class="dropdown-icon fe fe-lock"></i> {{ __('passwords.change_password') }}
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="dropdown-icon fe fe-log-out"></i> {{ __('auth.logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            <input type="submit" value="{{ __('auth.logout') }}" style="display: none;">
            @csrf
        </form>
    </div>
</div>
