@extends('layouts.app')

@section('title', __('loan.list'))

@section('content')
<h1 class="page-header">
    <div class="pull-right">
        @can('create', new App\Loan)
            {{ link_to_route('loans.create', __('loan.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    {{ __('loan.list') }}
    <small>{{ __('app.total') }} : {{ $loans->total() }} {{ __('loan.loan') }}</small>
</h1>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                {!! FormField::text('q', ['label' => __('loan.search'), 'placeholder' => __('loan.search_text'), 'class' => 'input-sm']) !!}
                {{ Form::submit(__('loan.search'), ['class' => 'btn btn-sm']) }}
                {{ link_to_route('loans.index', __('app.reset')) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('loan.partner') }}</th>
                        <th>{{ __('loan.type') }}</th>
                        <th class="text-right">{{ __('loan.amount') }}</th>
                        <th>{{ __('app.description') }}</th>
                        <th class="text-center">{{ __('loan.start_date') }}</th>
                        <th class="text-center">{{ __('loan.end_date') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $key => $loan)
                    <tr>
                        <td class="text-center">{{ $loans->firstItem() + $key }}</td>
                        <td>{{ $loan->partner->name }}</td>
                        <td>{{ $loan->type }}</td>
                        <td class="text-right">{{ $loan->amount_string }}</td>
                        <td>{{ $loan->description }}</td>
                        <td>{{ $loan->start_date }}</td>
                        <td>{{ $loan->end_date }}</td>
                        <td class="text-center">
                            @can('view', $loan)
                                {{ link_to_route(
                                    'loans.show',
                                    __('app.show'),
                                    [$loan],
                                    ['class' => 'btn btn-default btn-xs', 'id' => 'show-loan-' . $loan->id]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="panel-body">{{ $loans->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
