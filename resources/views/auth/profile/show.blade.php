@extends('layouts.settings')

@section('title', __('auth.profile').' - '.$user->name)

@section('content_settings')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@yield('title')</h3></div>
            <table class="table table-sm card-table">
                <tbody>
                    <tr><td>{{ __('user.name') }}</td><td>{{ $user->name }}</td></tr>
                    <tr><td>{{ __('user.email') }}</td><td>{{ $user->email }}</td></tr>
                    <tr><td>{{ __('user.account_start_date') }}</td><td>{{ $user->account_start_date }}</td></tr>
                </tbody>
            </table>
            <div class="card-footer">
                <a href="{{ route('profile.edit') }}" class="btn btn-success">{{ __('user.profile_edit') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
