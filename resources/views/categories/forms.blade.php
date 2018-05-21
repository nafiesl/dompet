@if (request('action') == 'create')
@can('create', new App\Category)
    <div id="categoryModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('categories.index', '&times;', [], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('category.create') }}</h4>
                </div>
                {!! Form::open(['route' => 'categories.store']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('name', ['required' => true, 'label' => __('category.name')]) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label for="color" class="control-label">{{ __('category.color') }}</label>
                                <div id="color" class="input-group colorpicker-component">
                                    <input name="color" type="text" value="#00AABB" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['label' => __('category.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('category.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'edit' && $editableCategory)
@can('update', $editableCategory)
    <div id="categoryModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('categories.index', '&times;', [], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('category.edit') }}</h4>
                </div>
                {!! Form::model($editableCategory, ['route' => ['categories.update', $editableCategory], 'method' => 'patch']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            {!! FormField::text('name', ['required' => true, 'label' => __('category.name')]) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required">
                                <label for="color" class="control-label">{{ __('category.color') }}</label>
                                <div id="color" class="input-group colorpicker-component">
                                    <input name="color" type="text" value="{{ $editableCategory->color }}" class="form-control" />
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! FormField::textarea('description', ['label' => __('category.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('category.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
                    @can('delete', $editableCategory)
                        {!! link_to_route(
                            'categories.index',
                            __('app.delete'),
                            ['action' => 'delete', 'id' => $editableCategory->id],
                            ['id' => 'del-category-'.$editableCategory->id, 'class' => 'btn btn-danger pull-left']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'delete' && $editableCategory)
@can('delete', $editableCategory)
    <div id="categoryModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('categories.index', '&times;', [], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('category.delete') }} {{ $editableCategory->type }}</h4>
                </div>
                <div class="modal-body">
                    <label class="control-label">{{ __('category.name') }}</label>
                    <p>{!! $editableCategory->name_label !!}</p>
                    <label class="control-label">{{ __('category.description') }}</label>
                    <p>{{ $editableCategory->description }}</p>
                    {!! $errors->first('category_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="modal-body">{{ __('app.delete_confirm') }}</div>
                <div class="modal-footer">
                    {!! FormField::delete(
                        ['route' => ['categories.destroy', $editableCategory], 'class' => ''],
                        __('app.delete_confirm_button'),
                        ['class'=>'btn btn-danger'],
                        [
                            'category_id' => $editableCategory->id,
                        ]
                    ) !!}
                    {{ link_to_route('categories.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
                </div>
            </div>
        </div>
    </div>
@endcan
@endif
