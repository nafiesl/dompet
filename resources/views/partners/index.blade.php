@extends('layouts.app')

@section('title', __('partner.list'))

@section('content')
<h1 class="page-header">
    <div class="pull-right">
        @can('create', new App\Partner)
            {{ link_to_route('partners.index', __('partner.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    {{ __('partner.list') }}
    <small>{{ __('app.total') }} : {{ $partners->total() }} {{ __('partner.partner') }}</small>
</h1>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                {!! FormField::text('q', ['label' => __('partner.search'), 'placeholder' => __('partner.search_text'), 'class' => 'input-sm']) !!}
                {{ Form::submit(__('partner.search'), ['class' => 'btn btn-sm']) }}
                {{ link_to_route('partners.index', __('app.reset')) }}
                {{ Form::close() }}
            </div>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.name') }}</th>
                        <th>{{ __('partner.description') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partners as $key => $partner)
                    <tr>
                        <td class="text-center">{{ $partners->firstItem() + $key }}</td>
                        <td>{{ $partner->name }}</td>
                        <td>{{ $partner->description }}</td>
                        <td class="text-center">
                            @can('update', $partner)
                                {{ link_to_route(
                                    'partners.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $partner->id] + Request::only('page', 'q'),
                                    ['id' => 'edit-partner-'.$partner->id]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="panel-body">{{ $partners->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('partners.forms')
        @endif
    </div>
</div>
@endsection
