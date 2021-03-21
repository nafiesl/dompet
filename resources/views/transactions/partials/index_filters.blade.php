{{ Form::open(['method' => 'get','class' => 'form-inline']) }}
    {!! FormField::text('query', [
        'value' => request('query'), 'label' => false,
        'class' => 'input-sm mr-2', 'placeholder' => __('transaction.search_text'),
    ]) !!}
    {{ Form::select('date', get_dates(), $date, ['class' => 'form-control input-sm mr-2', 'placeholder' => '--']) }}
    {{ Form::select('month', get_months(), $month, ['class' => 'form-control input-sm mr-2']) }}
    {{ Form::select('year', get_years(), $year, ['class' => 'form-control input-sm mr-2']) }}
    {!! FormField::select('category_id', $categories, ['label' => false, 'value' => request('category_id'), 'placeholder' => __('category.all'), 'class' => 'input-sm mr-2']) !!}
    {!! FormField::select('partner_id', $partners, ['label' => false, 'value' => request('partner_id'), 'placeholder' => __('partner.all'), 'class' => 'input-sm mr-2']) !!}
    {{ Form::submit(__('app.submit'), ['class' => 'btn btn-primary btn-sm mr-2']) }}
    {{ link_to_route('transactions.index', __('app.reset'), [], ['class' => 'btn btn-secondary btn-sm mr-2']) }}
    {{ link_to_route('transactions.exports.csv', __('transaction.download'), request()->all(), ['class' => 'btn btn-info btn-sm']) }}
{{ Form::close() }}
