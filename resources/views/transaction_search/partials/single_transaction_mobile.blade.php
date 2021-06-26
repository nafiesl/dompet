<span class="float-right">{{ $transaction->amount_string }}</span>
{{ link_to_route('transactions.index', $transaction->date, [
    'date' => $transaction->date_only,
    'month' => substr($transaction->date, 5, 2),
    'year' => substr($transaction->date, 0, 4),
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
