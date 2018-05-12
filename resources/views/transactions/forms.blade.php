@if (Request::get('action') == 'create')
@can('create', new App\Transaction)
    {!! Form::open(['route' => 'transactions.store']) !!}
    {!! FormField::text('date', ['required' => true, 'label' => trans('transaction.date')]) !!}
    {!! FormField::text('amount', ['required' => true, 'label' => trans('transaction.amount')]) !!}
    {!! FormField::textarea('description', ['required' => true, 'label' => trans('transaction.description')]) !!}
    {!! Form::submit(trans('transaction.create'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('transactions.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endcan
@endif
@if (Request::get('action') == 'edit' && $editableTransaction)
@can('update', $editableTransaction)
    {!! Form::model($editableTransaction, ['route' => ['transactions.update', $editableTransaction],'method' => 'patch']) !!}
    {!! FormField::text('date', ['required' => true, 'label' => trans('transaction.date')]) !!}
    {!! FormField::text('amount', ['required' => true, 'label' => trans('transaction.amount')]) !!}
    {!! FormField::textarea('description', ['required' => true, 'label' => trans('transaction.description')]) !!}
    @if (request('date'))
        {{ Form::hidden('date', request('date')) }}
    @endif
    {!! Form::submit(trans('transaction.update'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('transactions.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endcan
@endif
@if (Request::get('action') == 'delete' && $editableTransaction)
@can('delete', $editableTransaction)
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ trans('transaction.delete') }}</h3></div>
        <div class="panel-body">
            <label class="control-label">{{ trans('transaction.date') }}</label>
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
