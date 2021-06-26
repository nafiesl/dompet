@extends('layouts.app')

@section('title', __('transaction.search'))

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ __('transaction.search') }}</h1>
    <div class="page-subtitle">{{ $transactions->count() }} {{ __('transaction.transaction') }}</div>
    <div class="page-options d-flex">
        {{ link_to_route('transactions.index', __('transaction.back_to_index'), [], ['class' => 'btn btn-secondary float-right']) }}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                    {!! Form::text('search_query', $searchQuery, ['class' => 'form-control form-control-sm mr-2', 'placeholder' => __('transaction.search_text')]) !!}
                    <div class="form-group mt-4 mt-sm-0">
                        {{ Form::submit(__('app.search'), ['class' => 'btn btn-primary btn-sm mr-2']) }}
                        {{ link_to_route('transaction_search.index', __('app.reset'), [], ['class' => 'btn btn-secondary btn-sm']) }}
                    </div>
                {{ Form::close() }}
            </div>
            @if ($searchQuery)
                <table class="table table-sm table-responsive-sm table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center col-md-1">{{ __('app.table_no') }}</th>
                            <th class="text-center col-md-2">{{ __('app.date') }}</th>
                            <th class="col-md-7">{{ __('transaction.description') }}</th>
                            <th class="text-right col-md-2">{{ __('transaction.amount') }}</th>
                            <th class="text-center">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $key => $transaction)
                        <tr>
                            <td class="text-center">{{ 1 + $key }}</td>
                            <td class="text-center">{{ $transaction->date }}</td>
                            <td>
                                {{ $transaction->description }}
                                <div class="float-right">
                                    {!! optional($transaction->partner)->name_label !!}
                                    {!! optional($transaction->category)->name_label !!}
                                    {!! optional($transaction->loan)->type_label !!}
                                </div>
                            </td>
                            <td class="text-right">{{ $transaction->amount_string }}</td>
                            <td class="text-center">&nbsp;</td>
                        </tr>
                        @empty
                        <tr><td colspan="5">{{ __('transaction.not_found') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
