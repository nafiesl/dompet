@extends('layouts.app')

@section('title', __('user.profile_edit'))

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">@yield('title')</h3></div>
            {{ Form::model($user, ['route' => 'profile.update', 'method' => 'patch']) }}
                <div class="panel-body">
                    {!! FormField::text('name', ['required' => true]) !!}
                    {!! FormField::email('email', ['required' => true]) !!}
                </div>
                <div class="panel-footer">
                    {{ Form::submit(__('user.profile_update'), ['class' => 'btn btn-success']) }}
                    {{ link_to_route('profile.show', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
