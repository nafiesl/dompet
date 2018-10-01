@extends('layouts.app')

@section('title', __('transaction.list'))

@section('content')
<h3 class="page-header">
    <div class="pull-right">
    @can('create', new App\Transaction)
        {{ link_to_route('transactions.index', __('transaction.add_income'), ['action' => 'add-income', 'month' => $month, 'year' => $year], ['class' => 'btn btn-success']) }}
        {{ link_to_route('transactions.index', __('transaction.add_spending'), ['action' => 'add-spending', 'month' => $month, 'year' => $year], ['class' => 'btn btn-danger']) }}
    @endcan
    </div>
    {{ __('transaction.list') }}
    <small>{{ __('app.total') }} : {{ $transactions->count() }} {{ __('transaction.transaction') }}</small>
</h3>
<div class="row">
    <div class="col-md-12">
        @include('transactions.partials.stats')
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get','class' => 'form-inline']) }}
                {!! FormField::text('query', [
                    'value' => request('query'), 'label' => false,
                    'class' => 'input-sm', 'placeholder' => __('transaction.search_text'),
                ]) !!}
                {{ Form::select('month', getMonths(), $month, ['class' => 'form-control input-sm']) }}
                {{ Form::select('year', getYears(), $year, ['class' => 'form-control input-sm']) }}
                {!! FormField::select('category_id', $categories, ['label' => false, 'value' => request('category_id'), 'placeholder' => __('category.all'), 'class' => 'input-sm']) !!}
                {!! FormField::select('partner_id', $partners, ['label' => false, 'value' => request('partner_id'), 'placeholder' => __('partner.all'), 'class' => 'input-sm']) !!}
                {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm']) }}
                {{ link_to_route('transactions.index', __('app.reset')) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th class="text-center">{{ __('app.date') }}</th>
                        <th>{{ __('transaction.description') }}</th>
                        <th class="text-right">{{ __('transaction.amount') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
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
                            <span class="pull-right">
                                @php
                                    $categoryRoute = route('categories.show', [
                                        $transaction->category_id,
                                        'start_date' => $year.'-'.$month.'-01',
                                        'end_date' => $year.'-'.$month.'-'.date('t'),
                                    ]);
                                @endphp
                                <a href="{{ $categoryRoute }}">{!! optional($transaction->category)->name_label !!}</a>
                            </span>
                            {{ $transaction->description }}
                        </td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                        <td class="text-center">
                            @can('update', $transaction)
                                {!! link_to_route(
                                    'transactions.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $transaction->id] + request(['month', 'year', 'query', 'category_id']),
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
                    <tr><th colspan="5" class="text-right">&nbsp;</th></tr>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('transaction.start_balance') }}</th>
                        <th class="text-right">
                            @if ($transactions->last())
                                {{ formatNumber(balance(Carbon\Carbon::parse($transactions->last()->date)->subDay()->format('Y-m-d'))) }}
                            @else
                                0
                            @endif
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('transaction.income_total') }}</th>
                        <th class="text-right">{{ formatNumber($incomeTotal) }}</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('transaction.spending_total') }}</th>
                        <th class="text-right">{{ formatNumber($spendingTotal) }}</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">{{ __('transaction.end_balance') }}</th>
                        <th class="text-right">
                            @if ($transactions->first())
                                {{ formatNumber(balance($transactions->first()->date)) }}
                            @else
                                0
                            @endif
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
