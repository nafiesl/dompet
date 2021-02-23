<div class="header py-4">
    <div class="container">
        <div class="d-flex">
            <a class="navbar-brand" href="{{ route('home') }}">
                @guest
                    {{ config('app.name', 'Laravel') }}
                @else
                    {{ auth()->user()->name }}
                @endguest
            </a>
            <div class="d-flex order-lg-2 ml-auto">
                @include('layouts.partials.top-nav-right')
            </div>
            <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
            </a>
        </div>
    </div>
</div>
<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
            @include('layouts.partials.primary-menu')
        </div>
    </div>
</div>
