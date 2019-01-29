@extends('layouts.app')

@section('title', __('auth.profile').' - '.$user->name)

@section('content')
<h3 class="page-header">@yield('title')</h3>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">@yield('title')</h3></div>
            <table class="table table-sm panel-table">
                <tbody>
                    <tr><td>{{ __('user.name') }}</td><td>{{ $user->name }}</td></tr>
                    <tr><td>{{ __('user.email') }}</td><td>{{ $user->email }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
