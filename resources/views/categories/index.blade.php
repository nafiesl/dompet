@extends('layouts.app')

@section('title', trans('category.list'))

@section('content')
<h1 class="page-header">
    <div class="pull-right">
    @can('create', new App\Category)
        {{ link_to_route('categories.index', trans('category.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
    @endcan
    </div>
    {{ trans('category.list') }}
    <small>{{ trans('app.total') }} : {{ $categories->total() }} {{ trans('category.category') }}</small>
</h1>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                {{ Form::open(['method' => 'get','class' => 'form-inline']) }}
                {!! FormField::text('q', ['value' => request('q'), 'label' => trans('category.search'), 'class' => 'input-sm']) !!}
                {{ Form::submit(trans('category.search'), ['class' => 'btn btn-sm']) }}
                {{ link_to_route('categories.index', trans('app.reset')) }}
                {{ Form::close() }}
            </div>
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
                        <td class="text-center">{{ $categories->firstItem() + $key }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td class="text-center">
                        @can('update', $category)
                            {!! link_to_route(
                                'categories.index',
                                trans('app.edit'),
                                ['action' => 'edit', 'id' => $category->id] + Request::only('page', 'q'),
                                ['id' => 'edit-category-'.$category->id]
                            ) !!} |
                        @endcan
                        @can('delete', $category)
                            {!! link_to_route(
                                'categories.index',
                                trans('app.delete'),
                                ['action' => 'delete', 'id' => $category->id] + Request::only('page', 'q'),
                                ['id' => 'del-category-'.$category->id]
                            ) !!}
                        @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="panel-body">{{ $categories->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('categories.forms')
        @endif
    </div>
</div>
@endsection
