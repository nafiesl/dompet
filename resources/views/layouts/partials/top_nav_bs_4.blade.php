<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @auth
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="xs-navbar" title="{{ __('transaction.current_balance') }}">
                        <img src="{{ asset('images/icons8-coins-16.png') }}" alt=""> {{ format_number(balance(date('Y-m-d'))) }}
                    </a>
                </li>
                @include ('layouts.partials.lang_switcher')
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                <li class="nav-item">
                    <a class="xs-navbar" href="{{ route('transactions.index') }}" title="{{ __('transaction.transaction') }}">
                        <i class="fe fe-repeat"></i>
                        <span class="d-none d-sm-inline">{{ __('transaction.transaction') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="xs-navbar" href="{{ route('loans.index') }}" title="{{ __('loan.loan') }}">
                        <i class="fe fe-refresh-cw"></i>
                        <span class="d-none d-sm-inline">{{ __('loan.loan') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="xs-navbar" href="{{ route('reports.index') }}" title="{{ __('report.report') }}">
                        <i class="fe fe-activity"></i>
                        <span class="d-none d-sm-inline">{{ __('report.report') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="xs-navbar" href="{{ route('profile.show') }}" title="{{ __('settings.settings') }}">
                        <i class="fe fe-settings"></i>
                        <span class="d-none d-sm-inline">{{ __('settings.settings') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="xs-navbar" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        <i class="fe fe-log-out"></i> <span class="d-none d-sm-inline">{{ __('Logout') }}</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        <input type="submit" value="{{ __('auth.logout') }}" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
