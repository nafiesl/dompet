@extends('layouts.app')

@section('title', trans('transaction.list'))

@section('content')
<h1 class="page-header">
    <div class="pull-right">
    @can('create', new App\Transaction)
        {{ link_to_route('transactions.index', trans('transaction.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
    @endcan
    </div>
    {{ trans('transaction.list') }}
    <small>{{ trans('app.total') }} : {{ $transactions->total() }} {{ trans('transaction.transaction') }}</small>
</h1>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get','class' => 'form-inline']) }}
                {!! FormField::text('q', ['value' => request('q'), 'label' => trans('transaction.search'), 'class' => 'input-sm']) !!}
                {{ Form::submit(trans('transaction.search'), ['class' => 'btn btn-sm']) }}
                {{ link_to_route('transactions.index', trans('app.reset')) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('app.table_no') }}</th>
                        <th>{{ trans('transaction.name') }}</th>
                        <th>{{ trans('transaction.description') }}</th>
                        <th class="text-center">{{ trans('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ $transactions->firstItem() + $key }}</td>
                        <td>{{ $transaction->name }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-center">
                        @can('update', $transaction)
                            {!! link_to_route(
                                'transactions.index',
                                trans('app.edit'),
                                ['action' => 'edit', 'id' => $transaction->id] + Request::only('page', 'q'),
                                ['id' => 'edit-transaction-'.$transaction->id]
                            ) !!} |
                        @endcan
                        @can('delete', $transaction)
                            {!! link_to_route(
                                'transactions.index',
                                trans('app.delete'),
                                ['action' => 'delete', 'id' => $transaction->id] + Request::only('page', 'q'),
                                ['id' => 'del-transaction-'.$transaction->id]
                            ) !!}
                        @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="panel-body">{{ $transactions->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('transactions.forms')
        @endif
    </div>
</div>
@endsection
