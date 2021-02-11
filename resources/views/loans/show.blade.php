@extends('layouts.app')

@section('title', __('loan.detail'))

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ __('loan.detail') }}</h3></div>
            <table class="table table-condensed">
                <tbody>
                    <tr><td>{{ __('loan.partner') }}</td><td>{{ $loan->partner->name }}</td></tr>
                    <tr><td>{{ __('loan.type') }}</td><td>{{ $loan->type_id }}</td></tr>
                    <tr><td>{{ __('loan.amount') }}</td><td>{{ $loan->amount }}</td></tr>
                    <tr><td>{{ __('loan.planned_payment_count') }}</td><td>{{ $loan->planned_payment_count }}</td></tr>
                    <tr><td>{{ __('loan.description') }}</td><td>{{ $loan->description }}</td></tr>
                    <tr><td>{{ __('loan.start_date') }}</td><td>{{ $loan->start_date }}</td></tr>
                    <tr><td>{{ __('loan.end_date') }}</td><td>{{ $loan->end_date }}</td></tr>
                </tbody>
            </table>
            <div class="panel-footer">
                @can('update', $loan)
                    {{ link_to_route('loans.edit', __('loan.edit'), [$loan], ['class' => 'btn btn-warning', 'id' => 'edit-loan-'.$loan->id]) }}
                @endcan
                {{ link_to_route('loans.index', __('loan.back_to_index'), [], ['class' => 'btn btn-default']) }}
            </div>
        </div>
    </div>
</div>
@endsection
