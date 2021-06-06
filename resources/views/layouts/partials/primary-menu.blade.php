<div class="col-lg order-lg-first">
    <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
        <li class="nav-item">
            <a href="{{ route('transactions.index') }}" class="nav-link {{ Request::segment(1) == 'transactions' ? 'active' : '' }}">
                <i class="fe fe-repeat"></i> {{ __('transaction.transaction') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('loans.index') }}" class="nav-link {{ Request::segment(1) == 'loans' ? 'active' : '' }}">
                <i class="fe fe-refresh-cw"></i> {{ __('loan.loan') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('reports.index') }}" class="nav-link {{ Request::segment(1) == 'report' ? 'active' : '' }}">
                <i class="fe fe-activity"></i> {{ __('report.report') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('profile.show') }}" class="nav-link {{ Request::segment(1) == 'profile' ? 'active' : '' }}">
                <i class="fe fe-settings"></i> {{ __('settings.settings') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fe fe-log-out"></i> {{ __('auth.logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                <input type="submit" value="{{ __('auth.logout') }}" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>