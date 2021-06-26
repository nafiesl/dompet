<span class="float-right">{{ $transaction->amount_string }}</span>
{{ link_to_route('transactions.index', $transaction->date, [
    'query' => $searchQuery,
    'date' => $transaction->date_only,
    'month' => $transaction->month,
    'year' => $transaction->year,
]) }}
<div>
    {{ $transaction->description }}
</div>
<div style="margin-bottom: 6px;">
    {!! optional($transaction->loan)->type_label !!}
    {!! optional($transaction->partner)->name_label !!}
    {!! optional($transaction->category)->name_label !!}
</div>
<hr class="my-2">
