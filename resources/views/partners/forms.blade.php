@if (Request::get('action') == 'create')
@can('create', new App\Partner)
    {{ Form::open(['route' => 'partners.store']) }}
    {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
    {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
    {{ Form::submit(__('partner.create'), ['class' => 'btn btn-success']) }}
    {{ link_to_route('partners.index', __('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {{ Form::close() }}
@endcan
@endif
@if (Request::get('action') == 'edit' && $editablePartner)
@can('update', $editablePartner)
    {{ Form::model($editablePartner, ['route' => ['partners.update', $editablePartner], 'method' => 'patch']) }}
    {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
    {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
    @if (request('q'))
        {{ Form::hidden('q', request('q')) }}
    @endif
    @if (request('page'))
        {{ Form::hidden('page', request('page')) }}
    @endif
    {{ Form::submit(__('partner.update'), ['class' => 'btn btn-success']) }}
    {{ link_to_route('partners.index', __('app.cancel'), Request::only('page', 'q'), ['class' => 'btn btn-default']) }}
    @can('delete', $editablePartner)
        {{ link_to_route(
            'partners.index',
            __('app.delete'),
            ['action' => 'delete', 'id' => $editablePartner->id] + Request::only('page', 'q'),
            ['id' => 'del-partner-'.$editablePartner->id, 'class' => 'btn btn-danger pull-right']
        ) }}
    @endcan
    {{ Form::close() }}
@endcan
@endif
@if (Request::get('action') == 'delete' && $editablePartner)
@can('delete', $editablePartner)
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ __('partner.delete') }}</h3></div>
        <div class="panel-body">
            <label class="control-label text-primary">{{ __('partner.name') }}</label>
            <p>{{ $editablePartner->name }}</p>
            <label class="control-label text-primary">{{ __('partner.description') }}</label>
            <p>{{ $editablePartner->description }}</p>
            {!! $errors->first('partner_id', '<span class="form-error small">:message</span>') !!}
        </div>
        <hr style="margin:0">
        <div class="panel-body text-danger">{{ __('partner.delete_confirm') }}</div>
        <div class="panel-footer">
            {!! FormField::delete(
                ['route' => ['partners.destroy', $editablePartner]],
                __('app.delete_confirm_button'),
                ['class' => 'btn btn-danger'],
                [
                    'partner_id' => $editablePartner->id,
                    'page' => request('page'),
                    'q' => request('q'),
                ]
            ) !!}
            {{ link_to_route('partners.index', __('app.cancel'), Request::only('page', 'q'), ['class' => 'btn btn-default']) }}
        </div>
    </div>
@endcan
@endif
