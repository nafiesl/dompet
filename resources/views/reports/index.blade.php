@extends('layouts.app')

@section('title', __('report.report').' - '.$year)

@section('content')


{{ Form::open(['method' => 'get', 'class' => 'form-inline well well-sm']) }}
{{ Form::label('year', __('report.view_yearly_label'), ['class' => 'control-label']) }}
{{ Form::select('year', getYears(), $year, ['class' => 'form-control']) }}
{{ Form::submit(__('report.view_report'), ['class' => 'btn btn-info btn-sm']) }}
{{ link_to_route('reports.index', __('report.this_year'), [], ['class' => 'btn btn-default btn-sm']) }}
{{ Form::close() }}

<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">{{ __('report.graph') }} {{ $year }}</h3></div>
    <div class="panel-body">
        <strong>{{ 'Rp' }}</strong>
        <div id="yearly-chart" style="height: 250px;"></div>
        <div class="text-center"><strong>{{ __('time.month') }}</strong></div>
    </div>
</div>

<div class="panel panel-default table-responsive">
    <div class="panel-heading"><h3 class="panel-title">{{ __('report.detail') }}</h3></div>
    <div class="panel-body table-responsive">
        <table class="table table-condensed">
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
                @foreach(getMonths() as $monthNumber => $monthName)
                @php
                    $any = isset($data[$monthNumber]);
                @endphp
                <tr>
                    <td class="text-center">{{ monthId($monthNumber) }}</td>
                    <td class="text-center">{{ $any ? $data[$monthNumber]->count : 0 }}</td>
                    <td class="text-right">{{ formatNumber($income = ($any ? $data[$monthNumber]->income : 0)) }}</td>
                    <td class="text-right">{{ formatNumber($spending = ($any ? $data[$monthNumber]->spending : 0)) }}</td>
                    <td class="text-right">{{ formatNumber($difference = ($any ? $data[$monthNumber]->difference : 0)) }}</td>
                    <td class="text-center">
                        {{ link_to_route(
                            'transactions.index',
                            __('report.view_monthly'),
                            ['month' => $monthNumber, 'year' => $year],
                            [
                                'class' => 'btn btn-info btn-xs',
                                'title' => __('report.monthly', ['year_month' => monthId($monthNumber)]),
                                'title' => __('report.monthly', ['year_month' => monthId($monthNumber).' '.$year]),
                            ]
                        ) }}
                    </td>
                </tr>
                @php
                    $chartData[] = ['month' => monthId($monthNumber), 'income' => $income, 'spending' => $spending, 'difference' => $difference];
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">{{ trans('app.total') }}</th>
                    <th class="text-center">{{ $data->sum('count') }}</th>
                    <th class="text-right">{{ formatNumber($data->sum('income')) }}</th>
                    <th class="text-right">{{ formatNumber($data->sum('spending')) }}</th>
                    <th class="text-right">{{ formatNumber($data->sum('difference')) }}</th>
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
        labels: ["{{ __('transaction.income') }} {{ 'Rp' }}", "{{ __('transaction.spending') }} {{ 'Rp' }}", "{{ __('transaction.difference') }} {{ 'Rp' }}"],
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
