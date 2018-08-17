@extends('layouts.app')

@section('title', __('category.list'))

@section('content')
<h3 class="page-header">
    <div class="pull-right">
        @can('create', new App\Category)
            {{ link_to_route('categories.index', __('category.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    {{ __('category.list') }}
    <small>{{ __('app.total') }} : {{ $categories->count() }} {{ __('category.category') }}</small>
</h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('category.name') }}</th>
                        <th>{{ __('category.description') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $key => $category)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td>{!! $category->name_label !!}</td>
                        <td>{{ $category->description }}</td>
                        <td class="text-center">
                            @can('view', $category)
                                {{ link_to_route(
                                    'categories.show',
                                    __('category.view_transactions'),
                                    $category,
                                    ['class' => 'btn btn-xs btn-default']
                                ) }}
                            @endcan
                            @can('update', $category)
                                {{ link_to_route(
                                    'categories.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $category->id],
                                    [
                                        'id' => 'edit-category-'.$category->id,
                                        'class' => 'btn btn-xs btn-warning',
                                    ]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">{{ __('category.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('categories.forms')
        @endif
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/bootstrap-colorpicker.min.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/bootstrap-colorpicker.min.js')) }}
<script>
(function () {
    $('#categoryModal').modal({
        show: true,
        backdrop: 'static',
    });
    $('#color').colorpicker();
})();
</script>
@endpush
