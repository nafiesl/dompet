@extends('layouts.app')

@section('title', trans('transaction.list'))

@section('content')
<h1 class="page-header">
    <div class="pull-right">
    @can('create', new App\Transaction)
        {{ link_to_route('transactions.index', trans('transaction.add_income'), ['action' => 'add-income'], ['class' => 'btn btn-success']) }}
        {{ link_to_route('transactions.index', trans('transaction.add_spending'), ['action' => 'add-spending'], ['class' => 'btn btn-success']) }}
    @endcan
    </div>
    {{ trans('transaction.list') }}
    <small>{{ trans('app.total') }} : {{ $transactions->count() }} {{ trans('transaction.transaction') }}</small>
</h1>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get','class' => 'form-inline']) }}
                {!! FormField::text('date', ['value' => $date, 'label' => trans('transaction.search'), 'class' => 'input-sm']) !!}
                {{ Form::submit(trans('transaction.search'), ['class' => 'btn btn-sm']) }}
                {{ link_to_route('transactions.index', trans('app.reset')) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('app.table_no') }}</th>
                        <th class="text-center">{{ trans('app.date') }}</th>
                        <th class="text-center">{{ trans('transaction.transaction') }}</th>
                        <th>{{ trans('transaction.amount') }}</th>
                        <th>{{ trans('transaction.description') }}</th>
                        <th class="text-center">{{ trans('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td class="text-center">{{ $transaction->date }}</td>
                        <td class="text-center">{{ $transaction->in_out }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-center">
                        @can('update', $transaction)
                            {!! link_to_route(
                                'transactions.index',
                                trans('app.edit'),
                                ['action' => 'edit', 'id' => $transaction->id] + Request::only('page', 'date'),
                                ['id' => 'edit-transaction-'.$transaction->id]
                            ) !!} |
                        @endcan
                        @can('delete', $transaction)
                            {!! link_to_route(
                                'transactions.index',
                                trans('app.delete'),
                                ['action' => 'delete', 'id' => $transaction->id] + Request::only('page', 'date'),
                                ['id' => 'del-transaction-'.$transaction->id]
                            ) !!}
                        @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('transactions.forms')
        @endif
    </div>
</div>
@endsection
