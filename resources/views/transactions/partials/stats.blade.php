<div class="card table-responsive">
    <table class="table table-sm table-responsive-sm table-hover table-bordered">
        <tr>
            <td class="col-xs-2 text-center">{{ trans('transaction.income') }}</td>
            <td class="col-xs-2 text-center">{{ trans('transaction.spending') }}</td>
            <td class="col-xs-2 text-center">{{ trans('transaction.difference') }}</td>
        </tr>
        <tr>
            <td class="text-center lead" style="border-top: none;">{{ number_format($incomeTotal, 2) }}</td>
            <td class="text-center lead" style="border-top: none;">{{ number_format($spendingTotal, 2) }}</td>
            <td class="text-center lead" style="border-top: none;">{{ number_format($incomeTotal - $spendingTotal, 2) }}</td>
        </tr>
    </table>
</div>
