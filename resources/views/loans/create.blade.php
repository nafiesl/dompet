@extends('layouts.app')

@section('title', __('loan.create'))

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ __('loan.create') }}</h3></div>
            {{ Form::open(['route' => 'loans.store']) }}
            <div class="panel-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('loan.name')]) !!}
                {!! FormField::textarea('description', ['label' => __('loan.description')]) !!}
            </div>
            <div class="panel-footer">
                {{ Form::submit(__('loan.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('loans.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
