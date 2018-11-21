@extends('layouts.app')

@section('title', __('category.transactions'))

@section('content')

{{ link_to_route('categories.index', __('category.back_to_index'), [], ['class' => 'btn btn-sm btn-default pull-right']) }}
<h3 class="page-header">{{ $category->name }} <small>{{ __('category.transactions') }}</small></h3>

@include('transactions.partials.stats')

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get','class' => 'form-inline']) }}
                {!! FormField::text('query', [
                    'value' => request('query'), 'label' => __('transaction.search'),
                    'class' => 'input-sm', 'placeholder' => __('transaction.search_text'),
                    'style' => 'width:150px'
                ]) !!}
                {!! FormField::text('start_date', [
                    'value' => request('start_date'), 'label' => false, 'value' => $startDate,
                    'class' => 'input-sm date-select', 'placeholder' => __('time.start_date'),
                ]) !!}
                {!! FormField::text('end_date', [
                    'value' => request('end_date'), 'label' => false, 'value' => $endDate,
                    'class' => 'input-sm date-select', 'placeholder' => __('time.end_date'),
                ]) !!}
                {!! FormField::select('partner_id', $partners, ['label' => false, 'value' => request('partner_id'), 'placeholder' => __('partner.all'), 'class' => 'input-sm']) !!}
                {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm']) }}
                {{ link_to_route('categories.show', __('app.reset'), $category) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed table-bordered">
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
                            <span class="pull-right label label-default">{{ optional($transaction->partner)->name }}</span>
                        </td>
                        <td class="text-right">{{ $transaction->amount_string }}</td>
                        <td class="text-center">
                            @can('update', $transaction)
                                {!! link_to_route(
                                    'categories.show',
                                    __('app.edit'),
                                    [$category->id, 'action' => 'edit', 'id' => $transaction->id] + request(['start_date', 'end_date', 'query', 'partner_id']),
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
                            {{ formatNumber($transactions->sum(function ($transaction) {
                                return $transaction->in_out ? $transaction->amount : -$transaction->amount;
                            })) }}
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                </tfoot>
            </div>
        </table>
    </div>
</div>
@if(Request::has('action'))
@include('categories.partials.transaction-forms')
@endif
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/bootstrap-colorpicker.min.css')) }}
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/bootstrap-colorpicker.min.js')) }}
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
