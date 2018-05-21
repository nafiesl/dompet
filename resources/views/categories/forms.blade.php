@if (Request::get('action') == 'create')
@can('create', new App\Category)
    {!! Form::open(['route' => 'categories.store']) !!}
    {!! FormField::text('name', ['required' => true, 'label' => trans('category.name')]) !!}
    {!! FormField::textarea('description', ['label' => trans('category.description')]) !!}
    {!! Form::submit(trans('category.create'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('categories.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endcan
@endif
@if (Request::get('action') == 'edit' && $editableCategory)
@can('update', $editableCategory)
    {!! Form::model($editableCategory, ['route' => ['categories.update', $editableCategory],'method' => 'patch']) !!}
    {!! FormField::text('name', ['required' => true, 'label' => trans('category.name')]) !!}
    {!! FormField::textarea('description', ['label' => trans('category.description')]) !!}
    @if (request('q'))
        {{ Form::hidden('q', request('q')) }}
    @endif
    @if (request('page'))
        {{ Form::hidden('page', request('page')) }}
    @endif
    {!! Form::submit(trans('category.update'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('categories.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endcan
@endif
@if (Request::get('action') == 'delete' && $editableCategory)
@can('delete', $editableCategory)
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ trans('category.delete') }}</h3></div>
        <div class="panel-body">
            <label class="control-label">{{ trans('category.name') }}</label>
            <p>{{ $editableCategory->name }}</p>
            <label class="control-label">{{ trans('category.description') }}</label>
            <p>{{ $editableCategory->description }}</p>
            {!! $errors->first('category_id', '<span class="form-error small">:message</span>') !!}
        </div>
        <hr style="margin:0">
        <div class="panel-body">{{ trans('app.delete_confirm') }}</div>
        <div class="panel-footer">
            {!! FormField::delete(
                ['route' => ['categories.destroy', $editableCategory]],
                trans('app.delete_confirm_button'),
                ['class'=>'btn btn-danger'],
                [
                    'category_id' => $editableCategory->id,
                    'page' => request('page'),
                    'q' => request('q'),
                ]
            ) !!}
            {{ link_to_route('categories.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
        </div>
    </div>
@endcan
@endif
