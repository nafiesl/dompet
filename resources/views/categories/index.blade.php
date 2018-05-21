@extends('layouts.app')

@section('title', trans('category.list'))

@section('content')
<h3 class="page-header">
    <div class="pull-right">
    @can('create', new App\Category)
        {{ link_to_route('categories.index', trans('category.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
    @endcan
    </div>
    {{ trans('category.list') }}
    <small>{{ trans('app.total') }} : {{ $categories->count() }} {{ trans('category.category') }}</small>
</h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('app.table_no') }}</th>
                        <th>{{ trans('category.name') }}</th>
                        <th>{{ trans('category.description') }}</th>
                        <th class="text-center">{{ trans('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $key => $category)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td class="text-center">
                            @can('update', $category)
                                {!! link_to_route(
                                    'categories.index',
                                    trans('app.edit'),
                                    ['action' => 'edit', 'id' => $category->id],
                                    ['id' => 'edit-category-'.$category->id]
                                ) !!}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
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

@push('scripts')
<script>
(function () {
    $('#categoryModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
