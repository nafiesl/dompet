@extends('layouts.app')

@section('title', __('user.profile_edit'))

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">@yield('title')</h3></div>
            <form method="POST" action="{{ route('profile.update') }}" accept-charset="UTF-8">
                <div class="panel-body">
                    @csrf @method('patch')
                    <div class="form-group">
                        <label for="name" class="control-label">{{ __('user.name') }} <span class="form-required">*</span></label>
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $user->name) }}" required>
                        {!! $errors->first('name', '<span class="invalid-feedback small">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">{{ __('user.email') }} <span class="form-required">*</span></label>
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $user->email) }}" required>
                        {!! $errors->first('email', '<span class="invalid-feedback small">:message</span>') !!}
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="submit" value="{{ __('user.profile_update') }}" class="btn btn-success">
                    <a href="{{ route('profile.show') }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
