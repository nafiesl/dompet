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
                    <tr><td>{{ __('loan.amount') }}</td><td class="text-right">{{ $loan->amount_string }}</td></tr>
                    <tr><td>{{ __('loan.planned_payment_count') }}</td><td class="text-right">{{ $loan->planned_payment_count }}</td></tr>
                    <tr><td>{{ __('loan.payment_total') }}</td><td class="text-right">{{ $loan->payment_total_string }}</td></tr>
                    <tr><td>{{ __('loan.payment_remaining') }}</td><td class="text-right">{{ $loan->payment_remaining_string }}</td></tr>
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
                @can('update', $loan)
                    @if(Request::get('action') != 'add_transaction')
                        {{ link_to_route(
                            'loans.show',
                            __('loan.add_transaction'),
                            [$loan, 'action' => 'add_transaction'],
                            ['class' => 'btn btn-success', 'id' => 'add_transaction-'.$loan->id]
                        ) }}
                    @endif
                @endcan
            </div>
            {{ __('transaction.transaction') }}
        </h3>
        @can('update', $loan)
            @if(Request::has('action'))
                @include('loans.partials.single_actions')
            @endif
        @endcan
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
                    @foreach ($transactions as $key => $transaction)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td class="text-center text-middle">{{ $transaction->date }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">{{ __('app.total') }}</th>
                        <th class="text-right">
                            @php
                                $transactionSum = $transactions->sum(function ($transaction) {
                                    return $transaction->in_out ? $transaction->amount : -$transaction->amount;
                                });
                            @endphp
                            {{ number_format($transactionSum, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
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
