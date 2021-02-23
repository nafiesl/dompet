{{ Form::open(['method' => 'get','class' => 'form-inline']) }}
    {!! FormField::text('query', [
        'value' => request('query'), 'label' => false,
        'class' => 'input-sm', 'placeholder' => __('transaction.search_text'),
        'style' => 'width:150px'
    ]) !!}
    {!! FormField::text('start_date', [
        'value' => request('start_date'), 'label' => false, 'value' => $startDate,
        'class' => 'input-sm date-select', 'placeholder' => __('time.start_date'),
    ]) !!}
    {!! FormField::text('end_date', [
        'value' => request('end_date'), 'label' => false, 'value' => $endDate,
        'class' => 'input-sm date-select', 'placeholder' => __('time.end_date'),
    ]) !!}
    {!! FormField::select('category_id', $categories, ['label' => false, 'value' => request('category_id'), 'placeholder' => __('category.all'), 'class' => 'input-sm']) !!}
    {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('partners.show', __('app.reset'), $partner) }}
    {{ link_to_route('transactions.exports.by_partner', __('transaction.download'), [$partner] + request()->all(), ['class' => 'btn btn-info btn-sm float-right']) }}
{{ Form::close() }}
