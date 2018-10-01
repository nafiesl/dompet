@if (request('action') == 'create')
@can('create', new App\Partner)
    <div id="partnerModal" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('partners.index', '&times;', [], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('partner.create') }}</h4>
                </div>
                {!! Form::open(['route' => 'partners.store']) !!}
                <div class="modal-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
                    {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('partner.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('partners.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'edit' && $editablePartner)
@can('update', $editablePartner)
    <div id="partnerModal" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('partners.index', '&times;', [], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('partner.edit') }}</h4>
                </div>
                {!! Form::model($editablePartner, ['route' => ['partners.update', $editablePartner], 'method' => 'patch']) !!}
                <div class="modal-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
                    {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('partner.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('partners.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
                    @can('delete', $editablePartner)
                        {!! link_to_route(
                            'partners.index',
                            __('app.delete'),
                            ['action' => 'delete', 'id' => $editablePartner->id],
                            ['id' => 'del-partner-'.$editablePartner->id, 'class' => 'btn btn-danger pull-left']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'delete' && $editablePartner)
@can('delete', $editablePartner)
    <div id="partnerModal" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{ link_to_route('partners.index', '&times;', [], ['class' => 'close']) }}
                    <h4 class="modal-title">{{ __('partner.delete') }} {{ $editablePartner->type }}</h4>
                </div>
                <div class="modal-body">
                    <label class="control-label">{{ __('partner.name') }}</label>
                    <p>{!! $editablePartner->name_label !!}</p>
                    <label class="control-label">{{ __('partner.description') }}</label>
                    <p>{{ $editablePartner->description }}</p>
                    {!! $errors->first('partner_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="modal-body">{{ __('app.delete_confirm') }}</div>
                <div class="modal-footer">
                    {!! FormField::delete(
                        ['route' => ['partners.destroy', $editablePartner], 'class' => ''],
                        __('app.delete_confirm_button'),
                        ['class'=>'btn btn-danger'],
                        [
                            'partner_id' => $editablePartner->id,
                        ]
                    ) !!}
                    {{ link_to_route('partners.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
                </div>
            </div>
        </div>
    </div>
@endcan
@endif
