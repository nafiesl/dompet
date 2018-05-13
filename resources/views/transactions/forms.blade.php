@can('create', new App\Transaction)
@if (Request::get('action') == 'add-income')
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('transactions.index', '&times;', [], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('transaction.add_income') }}</h4>
                </div>
                {!! Form::open(['route' => 'transactions.store']) !!}
                {{ Form::hidden('in_out', 1) }}
                <div class="modal-body">
                    {!! FormField::text('date', ['required' => true, 'label' => trans('app.date')]) !!}
                    {!! FormField::text('amount', ['required' => true, 'label' => trans('transaction.amount')]) !!}
                    {!! FormField::textarea('description', ['required' => true, 'label' => trans('transaction.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(trans('transaction.add_income'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('transactions.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif
@if (Request::get('action') == 'add-spending')
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('transactions.index', '&times;', [], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('transaction.add_spending') }}</h4>
                </div>
                {!! Form::open(['route' => 'transactions.store']) !!}
                {{ Form::hidden('in_out', 0) }}
                <div class="modal-body">
                    {!! FormField::text('date', ['required' => true, 'label' => trans('app.date')]) !!}
                    {!! FormField::text('amount', ['required' => true, 'label' => trans('transaction.amount')]) !!}
                    {!! FormField::textarea('description', ['required' => true, 'label' => trans('transaction.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(trans('transaction.add_spending'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('transactions.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif
@endcan
@if (Request::get('action') == 'edit' && $editableTransaction)
@can('update', $editableTransaction)
    <div id="transactionModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('transactions.index', '&times;', ['date' => $date], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('transaction.edit') }}</h4>
                </div>
                {!! Form::model($editableTransaction, ['route' => ['transactions.update', $editableTransaction],'method' => 'patch']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">{!! FormField::text('date', ['required' => true, 'label' => trans('app.date')]) !!}</div>
                        <div class="col-md-6">{!! FormField::text('amount', ['required' => true, 'label' => trans('transaction.amount')]) !!}</div>
                    </div>
                    {!! FormField::radios('in_out', [__('transaction.spending'), __('transaction.income')], ['required' => true, 'label' => trans('transaction.transaction')]) !!}
                    {!! FormField::textarea('description', ['required' => true, 'label' => trans('transaction.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(trans('transaction.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('transactions.index', trans('app.cancel'), ['date' => $date], ['class' => 'btn btn-default']) }}
                    @can('delete', $editableTransaction)
                        {!! link_to_route(
                            'transactions.index',
                            trans('app.delete'),
                            ['action' => 'delete', 'id' => $editableTransaction->id] + Request::only('page', 'date'),
                            ['id' => 'del-transaction-'.$editableTransaction->id, 'class' => 'btn btn-danger pull-right']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif
@if (Request::get('action') == 'delete' && $editableTransaction)
@can('delete', $editableTransaction)
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ trans('transaction.delete') }}</h3></div>
        <div class="panel-body">
            <label class="control-label">{{ trans('app.date') }}</label>
            <p>{{ $editableTransaction->date }}</p>
            <label class="control-label">{{ trans('transaction.amount') }}</label>
            <p>{{ $editableTransaction->amount }}</p>
            <label class="control-label">{{ trans('transaction.description') }}</label>
            <p>{{ $editableTransaction->description }}</p>
            {!! $errors->first('transaction_id', '<span class="form-error small">:message</span>') !!}
        </div>
        <hr style="margin:0">
        <div class="panel-body">{{ trans('app.delete_confirm') }}</div>
        <div class="panel-footer">
            {!! FormField::delete(
                ['route' => ['transactions.destroy', $editableTransaction]],
                trans('app.delete_confirm_button'),
                ['class'=>'btn btn-danger'],
                [
                    'transaction_id' => $editableTransaction->id,
                    'date' => request('date'),
                ]
            ) !!}
            {{ link_to_route('transactions.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
        </div>
    </div>
@endcan
@endif
