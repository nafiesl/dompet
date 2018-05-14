@extends('layouts.app')

@section('title', trans('transaction.list'))

@section('content')
<h1 class="page-header">
    <div class="pull-right">
    @can('create', new App\Transaction)
        {{ link_to_route('transactions.index', trans('transaction.add_income'), ['action' => 'add-income', 'date' => $date], ['class' => 'btn btn-success']) }}
        {{ link_to_route('transactions.index', trans('transaction.add_spending'), ['action' => 'add-spending', 'date' => $date], ['class' => 'btn btn-success']) }}
    @endcan
    </div>
    {{ trans('transaction.list') }}
    <small>{{ trans('app.total') }} : {{ $transactions->count() }} {{ trans('transaction.transaction') }}</small>
</h1>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get','class' => 'form-inline']) }}
                {!! FormField::text('query', [
                    'value' => request('query'), 'label' => __('transaction.search'),
                    'class' => 'input-sm', 'placeholder' => __('transaction.search_text'),
                ]) !!}
                {!! FormField::text('date', [
                    'value' => $date, 'label' => false,
                    'class' => 'input-sm date-select',
                    'style' => 'width:90px'
                ]) !!}
                {{ Form::submit(trans('transaction.search'), ['class' => 'btn btn-sm']) }}
                {{ link_to_route('transactions.index', trans('app.reset')) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('app.table_no') }}</th>
                        <th class="text-center">{{ trans('app.date') }}</th>
                        <th class="text-right">{{ trans('transaction.amount') }}</th>
                        <th>{{ trans('transaction.description') }}</th>
                        <th class="text-center">{{ trans('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td class="text-center">{{ $transaction->date }}</td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-center">
                            @can('update', $transaction)
                                {!! link_to_route(
                                    'transactions.index',
                                    trans('app.edit'),
                                    ['action' => 'edit', 'id' => $transaction->id] + Request::only('page', 'date'),
                                    ['id' => 'edit-transaction-'.$transaction->id]
                                ) !!}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5">{{ __('transaction.not_found') }}</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">{{ __('app.total') }}</th>
                        <th class="text-right">
                            {{ number_format($transactions->sum(function ($transaction) {
                                return $transaction->in_out ? $transaction->amount : -$transaction->amount;
                            }), 2) }}
                        </th>
                        <th colspan="2">&nbsp;</th>
                    </tr>
                </tfoot>
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

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('#transactionModal').modal({
        show: true,
        backdrop: 'static',
    });
    $('.date-select').datetimepicker({
        timepicker:false,
        format:'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false
    });
})();
</script>
@endpush
