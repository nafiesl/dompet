@extends('layouts.guest')

@section('title', __('Login'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-login mx-auto">
            <div class="card">
                <div class="card-header"><div class="card-title">{{ __('Login') }} {{ config('app.name') }}</div></div>
                <div class="card-body p-6">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="form-label">{{ __('auth.email') }}</label>

                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <a class="float-right small" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                {{ __('auth.password') }}
                            </label>

                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="custom-control-label" for="remember">
                                    {{ __('auth.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Login') }}
                            </button>
                            {{ link_to_route('register', __('auth.register'), [], ['class' => 'btn btn-link btn-block']) }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
