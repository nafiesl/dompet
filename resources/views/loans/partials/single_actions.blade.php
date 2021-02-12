@if (Request::get('action') == 'add_transaction')
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ __('loan.add_transaction') }}</h3></div>
        {{ Form::open(['route' => ['loans.transactions.store', $loan], 'method' => 'post']) }}
            <div class="panel-body">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        {!! FormField::text('date', ['required' => true, 'label' => __('app.date'), 'value' => old('date', today()->format('Y-m-d')), 'class' => 'date-select']) !!}
                    </div>
                    <div class="col-md-8">
                        {!! FormField::radios('in_out', $inOutOptions, ['required' => true, 'label' => __('transaction.transaction'), 'list_style' => 'inline']) !!}
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
            </div>
            <div class="panel-footer">
                {{ Form::submit(__('loan.add_transaction'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('loans.show', __('app.cancel'), [$loan], ['class' => 'btn btn-default']) }}
            </div>
        {{ Form::close() }}
    </div>
@endif
