@extends('layouts.app')

@section('content')
<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="{{ Request::segment(1) == 'profile' ? 'active' : '' }}">
        {!! link_to_route('profile.show', __('user.profile')) !!}
    </li>
</ul>
<br>
@yield('content_settings')
@endsection
