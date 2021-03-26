{{ Form::open(['method' => 'get','class' => 'form-inline']) }}
    {!! FormField::text('query', [
        'value' => request('query'), 'label' => false,
        'class' => 'mr-2', 'placeholder' => __('transaction.search_text'),
        'style' => 'width:150px'
    ]) !!}
    {!! FormField::text('start_date', [
        'value' => request('start_date'), 'label' => false, 'value' => $startDate,
        'class' => 'mr-2 date-select', 'placeholder' => __('time.start_date'),
    ]) !!}
    {!! FormField::text('end_date', [
        'value' => request('end_date'), 'label' => false, 'value' => $endDate,
        'class' => 'mr-2 date-select', 'placeholder' => __('time.end_date'),
    ]) !!}
    {!! FormField::select('partner_id', $partners, ['label' => false, 'value' => request('partner_id'), 'placeholder' => __('partner.all'), 'class' => 'mr-2']) !!}
    {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary mr-2']) }}
    {{ link_to_route('categories.show', __('app.reset'), $category, ['class' => 'btn btn-secondary mr-2']) }}
    {{ link_to_route('transactions.exports.by_category', __('transaction.download'), [$category] + request()->all(), ['class' => 'btn btn-info mr-2']) }}
{{ Form::close() }}
