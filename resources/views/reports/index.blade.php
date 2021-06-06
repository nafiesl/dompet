@extends('layouts.app')

@section('title', __('report.report').' - '.$year)

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ __('report.graph') }} {{ $year }}</h1>
    <div class="page-options d-flex">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline well well-sm']) }}
        {{ Form::label('year', __('report.view_yearly_label'), ['class' => 'control-label mr-2']) }}
        {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-2']) }}
        {{ Form::select('partner_id', $partners, $partnerId, ['class' => 'form-control mr-2', 'placeholder' => '-- '.__('partner.all').' --']) }}
        {{ Form::select('category_id', $categories, $categoryId, ['class' => 'form-control mr-2', 'placeholder' => '-- '.__('category.all').' --']) }}
        {{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info mr-2']) }}
        {{ link_to_route('reports.index', __('report.this_year'), [], ['class' => 'btn btn-secondary mr-2']) }}
        {{ Form::close() }}
    </div>
</div>

<div class="card">
    <div class="card-body">
        <strong>{{ auth()->user()->currency_code }}</strong>
        <div id="yearly-chart" style="height: 250px;"></div>
        <div class="text-center"><strong>{{ __('time.month') }}</strong></div>
    </div>
</div>

<div class="card table-responsive">
    <div class="card-header"><h3 class="card-title">{{ __('report.detail') }}</h3></div>
    <div class="card-body table-responsive">
        <table class="table table-sm table-responsive-sm table-hover">
            <thead>
                <th class="text-center">{{ __('time.month') }}</th>
                <th class="text-center">{{ __('transaction.transaction') }}</th>
                <th class="text-right">{{ __('transaction.income') }}</th>
                <th class="text-right">{{ __('transaction.spending') }}</th>
                <th class="text-right">{{ __('transaction.difference') }}</th>
                <th class="text-center">{{ __('app.action') }}</th>
            </thead>
            <tbody>
                @php $chartData = []; @endphp
                @foreach(get_months() as $monthNumber => $monthName)
                @php
                    $any = isset($data[$monthNumber]);
                @endphp
                <tr>
                    <td class="text-center">{{ month_id($monthNumber) }}</td>
                    <td class="text-center">{{ $any ? $data[$monthNumber]->count : 0 }}</td>
                    <td class="text-right text-nowrap">{{ format_number($income = ($any ? $data[$monthNumber]->income : 0)) }}</td>
                    <td class="text-right text-nowrap">{{ format_number($spending = ($any ? $data[$monthNumber]->spending : 0)) }}</td>
                    <td class="text-right text-nowrap">{{ format_number($difference = ($any ? $data[$monthNumber]->difference : 0)) }}</td>
                    <td class="text-center text-nowrap">
                        {{ link_to_route(
                            'transactions.index',
                            __('report.view_monthly'),
                            ['month' => $monthNumber, 'year' => $year, 'partner_id' => $partnerId],
                            [
                                'class' => 'btn btn-secondary btn-sm',
                                'title' => __('report.monthly', ['year_month' => month_id($monthNumber)]),
                                'title' => __('report.monthly', ['year_month' => month_id($monthNumber).' '.$year]),
                            ]
                        ) }}
                    </td>
                </tr>
                @php
                    $chartData[] = ['month' => month_id($monthNumber), 'income' => $income, 'spending' => $spending, 'difference' => $difference];
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">{{ trans('app.total') }}</th>
                    <th class="text-center">{{ $data->sum('count') }}</th>
                    <th class="text-right">{{ format_number($data->sum('income')) }}</th>
                    <th class="text-right">{{ format_number($data->sum('spending')) }}</th>
                    <th class="text-right">{{ format_number($data->sum('difference')) }}</th>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/morris.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/raphael.min.js')) }}
    {{ Html::script(url('js/plugins/morris.min.js')) }}
<script>
(function() {
    new Morris.Line({
        element: 'yearly-chart',
        data: {!! collect($chartData)->toJson() !!},
        xkey: 'month',
        ykeys: ['income', 'spending', 'difference'],
        labels: ["{{ __('transaction.income') }} {{ auth()->user()->currency_code }}", "{{ __('transaction.spending') }} {{ auth()->user()->currency_code }}", "{{ __('transaction.difference') }} {{ auth()->user()->currency_code }}"],
        parseTime:false,
        lineColors: ['green', 'orange', 'blue'],
        goals: [0],
        goalLineColors : ['red'],
        smooth: true,
        lineWidth: 2,
    });
})();
</script>
@endpush
