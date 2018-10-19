@extends('layouts.app')

@section('title', __('partner.list'))

@section('content')
<h3 class="page-header">
    <div class="pull-right">
        @can('create', new App\Partner)
            {{ link_to_route('partners.index', __('partner.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    {{ __('partner.list') }}
    <small>{{ __('app.total') }} : {{ $partners->count() }} {{ __('partner.partner') }}</small>
</h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default table-responsive">
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
                    @forelse($partners as $key => $partner)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td>{{ $partner->name }}</td>
                        <td>{{ $partner->description }}</td>
                        <td class="text-center">
                            @can('view', $partner)
                                {{ link_to_route(
                                    'partners.show',
                                    __('partner.view_transactions'),
                                    $partner,
                                    ['class' => 'btn btn-xs btn-default']
                                ) }}
                            @endcan
                            @can('update', $partner)
                                {{ link_to_route(
                                    'partners.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $partner->id],
                                    [
                                        'id' => 'edit-partner-'.$partner->id,
                                        'class' => 'btn btn-xs btn-warning',
                                    ]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">{{ __('partner.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('partners.forms')
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    $('#partnerModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
