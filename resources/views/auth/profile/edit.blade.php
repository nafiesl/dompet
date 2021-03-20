@extends('layouts.settings')

@section('title', __('user.profile_edit'))

@section('content_settings')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@yield('title')</h3></div>
            {{ Form::model($user, ['route' => 'profile.update', 'method' => 'patch']) }}
                <div class="card-body">
                    {!! FormField::text('name', ['required' => true]) !!}
                    {!! FormField::email('email', ['required' => true]) !!}
                    {!! FormField::text('account_start_date') !!}
                </div>
                <div class="card-footer">
                    {{ Form::submit(__('user.profile_update'), ['class' => 'btn btn-success']) }}
                    {{ link_to_route('profile.show', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
{{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
{{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('#account_start_date').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false
    });
})();
</script>
@endpush
