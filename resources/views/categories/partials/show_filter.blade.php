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
    {!! FormField::select('partner_id', $partners, ['label' => false, 'value' => request('partner_id'), 'placeholder' => __('partner.all'), 'class' => 'input-sm']) !!}
    {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('categories.show', __('app.reset'), $category) }}
    {{ link_to_route('transactions.exports.by_category', __('transaction.download'), [$category] + request()->all(), ['class' => 'btn btn-info btn-sm pull-right']) }}
{{ Form::close() }}
