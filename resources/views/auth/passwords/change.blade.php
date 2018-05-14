@extends('layouts.app')

@section('title', __('auth.change_password'))

@section('content')
<div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ __('auth.change_password') }}</h3></div>
        {!! Form::open(['route' => 'password.change', 'method' => 'patch']) !!}
        <div class="panel-body">
            {!! FormField::password('old_password', ['label'=> __('auth.old_password')]) !!}
            {!! FormField::password('password', ['label' => __('auth.new_password')]) !!}
            {!! FormField::password('password_confirmation', ['label' => __('auth.new_password_confirmation')]) !!}
        </div>
        <div class="panel-footer">
            {!! Form::submit(__('auth.change_password'), ['class' => 'btn btn-info']) !!}
            {!! link_to_route('home', __('app.cancel'), [], ['class' => 'btn btn-default']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
