@extends('layouts.app')

@section('title', __('loan.detail'))

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ __('loan.detail') }}</h3></div>
            <table class="table table-condensed">
                <tbody>
                    <tr><td>{{ __('loan.partner') }}</td><td>{{ $loan->partner->name }}</td></tr>
                    <tr><td>{{ __('loan.type') }}</td><td>{{ $loan->type }}</td></tr>
                    <tr><td>{{ __('loan.amount') }}</td><td>{{ $loan->amount_string }}</td></tr>
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
    <div class="col-md-7">
        <h3 class="page-header">
            <div class="pull-right">
                <div class="btn-group" role="group" aria-label="...">
                    {{ link_to_route(
                        'loans.transactions.create',
                        __('loan.add_transaction'),
                        [$loan],
                        ['class' => 'btn btn-success', 'id' => 'add_transaction-'.$loan->id]
                    ) }}
                </div>
            </div>
            {{ __('transaction.transaction') }}
        </h3>
        <div class="panel panel-default table-responsive">
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-center">{{ __('app.date') }}</th>
                        <th>{{ __('transaction.description') }}</th>
                        <th class="text-right">{{ __('transaction.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td class="text-center text-middle">{{ $transaction->date }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4">{{ __('transaction.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
