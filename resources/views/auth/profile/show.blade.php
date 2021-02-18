@extends('layouts.settings')

@section('title', __('auth.profile').' - '.$user->name)

@section('content_settings')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">@yield('title')</h3></div>
            <table class="table table-sm panel-table">
                <tbody>
                    <tr><td>{{ __('user.name') }}</td><td>{{ $user->name }}</td></tr>
                    <tr><td>{{ __('user.email') }}</td><td>{{ $user->email }}</td></tr>
                    <tr><td>{{ __('user.account_start_date') }}</td><td>{{ $user->account_start_date }}</td></tr>
                </tbody>
            </table>
            <div class="panel-footer">
                <a href="{{ route('profile.edit') }}" class="btn btn-success">{{ __('user.profile_edit') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
