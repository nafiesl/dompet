<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ route('home') }}">
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
                <a class="xs-navbar" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
                    <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span>&nbsp;
                    <span class="hidden-xs">{{ __('transaction.transaction') }}</span>
                </a>
                <a class="xs-navbar" href="{{ route('categories.index') }}" title="{{ __('category.category') }}">
                    <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>&nbsp;
                    <span class="hidden-xs">{{ __('category.category') }}</span>
                </a>
                <a class="xs-navbar" href="{{ route('password.change') }}" title="{{ __('auth.change_password') }}">
                    <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;
                    <span class="hidden-xs">{{ __('auth.change_password') }}</span>
                </a>
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
