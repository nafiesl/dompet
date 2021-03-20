@extends('layouts.settings')

@section('title', __('auth.change_password'))

@section('content_settings')
<div class="col-md-8 col-md-offset-2">
    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ __('auth.change_password') }}</h3></div>
        {!! Form::open(['route' => 'password.change', 'method' => 'patch']) !!}
        <div class="card-body">
            {!! FormField::password('old_password', ['label'=> __('auth.old_password')]) !!}
            {!! FormField::password('password', ['label' => __('auth.new_password')]) !!}
            {!! FormField::password('password_confirmation', ['label' => __('auth.new_password_confirmation')]) !!}
        </div>
        <div class="card-footer">
            {!! Form::submit(__('auth.change_password'), ['class' => 'btn btn-info']) !!}
            {!! link_to_route('home', __('app.cancel'), [], ['class' => 'btn btn-default']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
