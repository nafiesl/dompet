@if (Request::get('action') == 'create')
@can('create', new App\Transaction)
    {!! Form::open(['route' => 'transactions.store']) !!}
    {!! FormField::text('name', ['required' => true, 'label' => trans('transaction.name')]) !!}
    {!! FormField::textarea('description', ['label' => trans('transaction.description')]) !!}
    {!! Form::submit(trans('transaction.create'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('transactions.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endcan
@endif
@if (Request::get('action') == 'edit' && $editableTransaction)
@can('update', $editableTransaction)
    {!! Form::model($editableTransaction, ['route' => ['transactions.update', $editableTransaction],'method' => 'patch']) !!}
    {!! FormField::text('name', ['required' => true, 'label' => trans('transaction.name')]) !!}
    {!! FormField::textarea('description', ['label' => trans('transaction.description')]) !!}
    @if (request('q'))
        {{ Form::hidden('q', request('q')) }}
    @endif
    @if (request('page'))
        {{ Form::hidden('page', request('page')) }}
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
            <label class="control-label">{{ trans('transaction.name') }}</label>
            <p>{{ $editableTransaction->name }}</p>
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
                    'page' => request('page'),
                    'q' => request('q'),
                ]
            ) !!}
            {{ link_to_route('transactions.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
        </div>
    </div>
@endcan
@endif
