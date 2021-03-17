<div class="col-lg order-lg-first">
    <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}"><i class="fe fe-home"></i> Home</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('transactions.index') }}" class="nav-link {{ Request::segment(1) == 'transactions' ? 'active' : '' }}"><i class="fe fe-truck"></i> {{ __('transaction.transaction') }}</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('loans.index') }}" class="nav-link {{ Request::segment(1) == 'loans' ? 'active' : '' }}"><i class="fe fe-truck"></i> {{ __('loan.loan') }}</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('profile.show') }}" class="nav-link {{ Request::segment(1) == 'loans' ? 'active' : '' }}">
                <i class="fe fe-truck"></i> {{ __('settings.settings') }}
            </a>
        </li>
        @include ('layouts.partials.lang_switcher')
    </ul>
</div>
