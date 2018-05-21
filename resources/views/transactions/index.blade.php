@extends('layouts.app')

@section('title', trans('transaction.list'))

@section('content')
<h3 class="page-header">
    <div class="pull-right">
    @can('create', new App\Transaction)
        {{ link_to_route('transactions.index', trans('transaction.add_income'), ['action' => 'add-income', 'month' => $month, 'year' => $year], ['class' => 'btn btn-success']) }}
        {{ link_to_route('transactions.index', trans('transaction.add_spending'), ['action' => 'add-spending', 'month' => $month, 'year' => $year], ['class' => 'btn btn-success']) }}
    @endcan
    </div>
    {{ trans('transaction.list') }}
    <small>{{ trans('app.total') }} : {{ $transactions->count() }} {{ trans('transaction.transaction') }}</small>
</h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get','class' => 'form-inline']) }}
                {!! FormField::text('query', [
                    'value' => request('query'), 'label' => __('transaction.search'),
                    'class' => 'input-sm', 'placeholder' => __('transaction.search_text'),
                ]) !!}
                {{ Form::select('month', getMonths(), $month, ['class' => 'form-control input-sm']) }}
                {{ Form::select('year', getYears(), $year, ['class' => 'form-control input-sm']) }}
                {!! FormField::select('category_id', $categories, ['label' => false, 'placeholder' => __('category.all'), 'class' => 'input-sm']) !!}
                {{ Form::submit(trans('app.submit'), ['class' => 'btn btn-primary btn-sm']) }}
                {{ link_to_route('transactions.index', trans('app.reset')) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('app.table_no') }}</th>
                        <th class="text-center">{{ trans('app.date') }}</th>
                        <th>{{ trans('transaction.description') }}</th>
                        <th class="text-right">{{ trans('transaction.amount') }}</th>
                        <th class="text-center">{{ trans('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $key => $transaction)
                    @php
                        $groups = $transactions->where('date_only', $transaction->date_only);
                        $firstGroup = $groups->first();
                        $groupCount = $groups->count();
                    @endphp
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        @if ($firstGroup->id == $transaction->id)
                            <td class="text-center text-middle" rowspan="{{ $groupCount }}">
                                {{ link_to_route('transactions.index', $transaction->date_only, [
                                    'date' => $transaction->date_only,
                                    'month' => $month,
                                    'year' => $year,
                                    'category_id' => request('category_id'),
                                ]) }}
                            </td>
                        @endif
                        <td>
                            <span class="pull-right">{!! optional($transaction->category)->name_label !!}</span>
                            {{ $transaction->description }}
                        </td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                        <td class="text-center">
                            @can('update', $transaction)
                                {!! link_to_route(
                                    'transactions.index',
                                    trans('app.edit'),
                                    ['action' => 'edit', 'id' => $transaction->id] + Request::only('page', 'month', 'year'),
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
                        <th colspan="3" class="text-right">{{ __('app.total') }}</th>
                        <th class="text-right">
                            {{ number_format($transactions->sum(function ($transaction) {
                                return $transaction->in_out ? $transaction->amount : -$transaction->amount;
                            }), 2) }}
                        </th>
                        <th>&nbsp;</th>
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
