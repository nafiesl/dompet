@extends('layouts.app')

@section('content')
<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="{{ Request::segment(1) == 'profile' ? 'active' : '' }}">
        {!! link_to_route('profile.show', __('user.profile')) !!}
    </li>
    <li class="{{ Request::segment(1) == 'change-password' ? 'active' : '' }}">
        {!! link_to_route('password.change', __('auth.change_password')) !!}
    </li>
    <li class="{{ Request::segment(1) == 'categories' ? 'active' : '' }}">
        {!! link_to_route('categories.index', __('category.category')) !!}
    </li>
</ul>
<br>
@yield('content_settings')
@endsection
