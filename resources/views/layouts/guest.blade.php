<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield ('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/guest.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div class="container">
        <div class="header clearfix">
            @if (Route::has('login'))
            <nav>
                <ul class="nav nav-pills float-right">
                   @auth
                       <li role="presentation" class="active"><a href="{{ url('/home') }}">Home</a></li>
                   @else
                        <li role="presentation" class="active"><a href="{{ route('login') }}">Login</a></li>
                        <li role="presentation"><a href="{{ route('register') }}">Register</a></li>
                   @endauth
                </ul>
            </nav>
            @endif
            <h3 class="text-muted">{{ config('app.name', 'Laravel') }}</h3>
        </div>

        @yield ('content')

        <footer class="footer">
            <p>&copy; 2018 Nafies Luthfi.</p>
        </footer>

    </div> <!-- /container -->
</body>
</html>
