<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <!-- Right Side Of Navbar -->
        <div class="nav navbar-nav navbar-right" style="margin: 0;">
            <!-- Authentication Links -->
            @guest
                {{ link_to_route('login', __('auth.login'), [], ['class' => 'xs-navbar']) }}
                {{ link_to_route('register', __('auth.register'), [], ['class' => 'xs-navbar']) }}
            @else
                {{ link_to_route('transactions.index', __('transaction.transaction'), [], ['class' => 'xs-navbar']) }}
                {{ link_to_route('categories.index', __('category.category'), [], ['class' => 'xs-navbar']) }}
                {{ link_to_route('password.change', __('auth.change_password'), [], ['class' => 'xs-navbar']) }}
                <a class="xs-navbar" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    <input type="submit" value="{{ __('auth.logout') }}" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endguest
        </div>
    </div>
</nav>
