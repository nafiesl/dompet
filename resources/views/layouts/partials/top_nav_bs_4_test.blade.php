<nav class="navbar navbar-expand-sm navbar-light bg-white shadow-sm">
    <div class="container">
        <div class="navbar-header">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ route('home') }}">
                @guest
                    {{ config('app.name', 'Laravel') }}
                @else
                    {{ auth()->user()->name }}
                @endguest
            </a>
        </div>
        <div class="nav navbar-nav mx-2 flex-row">
            @auth
            <a href="{{ route('home') }}" class="xs-navbar mr-4" title="{{ __('transaction.current_balance') }}">
                <img src="{{ asset('images/icons8-coins-16.png') }}" alt=""> {{ format_number(balance(date('Y-m-d'))) }}
            </a>
            @include ('layouts.partials.lang_switcher')
            @endauth
        </div>

        <!-- Right Side Of Navbar -->
        <div class="nav navbar-nav ml-auto d-block mt-3 mt-md-0">
            <!-- Authentication Links -->
            <a class="xs-navbar mr-4" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
                <i class="fe fe-repeat"></i>
                <span class="d-none d-sm-inline">{{ __('transaction.transaction') }}</span>
            </a>
            <a class="xs-navbar mr-4" href="{{ route('loans.index') }}" title="{{ __('loan.loan') }}">
                <i class="fe fe-refresh-cw"></i>
                <span class="d-none d-sm-inline">{{ __('loan.loan') }}</span>
            </a>
            <a class="xs-navbar mr-4" href="{{ route('reports.index') }}" title="{{ __('report.report') }}">
                <i class="fe fe-activity"></i>
                <span class="d-none d-sm-inline">{{ __('report.report') }}</span>
            </a>
            <a class="xs-navbar mr-4" href="{{ route('profile.show') }}" title="{{ __('settings.settings') }}">
                <i class="fe fe-settings"></i>
                <span class="d-none d-sm-inline">{{ __('settings.settings') }}</span>
            </a>
            <a class="xs-navbar mr-4" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                <i class="fe fe-log-out"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                <input type="submit" value="{{ __('auth.logout') }}" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</nav>
