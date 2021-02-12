@extends('layouts.app')

@section('title', __('loan.add_transaction'))

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ __('loan.add_transaction') }}</h3></div>
            <div class="panel-body">
                {{ Form::open(['route' => ['loans.transactions.store', $loan], 'method' => 'post']) }}
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            {!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', today()->format('Y-m-d')), 'class' => 'date-select']) !!}
                        </div>
                        <div class="col-md-8">
                            {!! FormField::radios('in_out', [__('transaction.spending'), __('transaction.income')], ['required' => true, 'label' => __('transaction.in_out'), 'list_style' => 'inline']) !!}
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['required' => true, 'label' => __('app.description')]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('amount', ['required' => true, 'type' => 'number', 'min' => '0', 'label' => __('transaction.amount'), 'class' => 'text-right']) !!}
                        </div>
                        <div class="col-md-6">
                            {!! FormField::textDisplay('partner', $loan->partner->name) !!}
                        </div>
                    </div>
                    {{ Form::submit(__('loan.add_transaction'), ['class' => 'btn btn-success']) }}
                    {{ link_to_route('loans.show', __('app.back'), [$loan], ['class' => 'btn btn-default']) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
