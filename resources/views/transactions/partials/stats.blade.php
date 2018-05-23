<div class="panel panel-default table-responsive">
    <table class="table table-condensed table-bordered">
        <tr>
            <td class="col-xs-2 text-center">{{ trans('transaction.income_total') }}</td>
            <td class="col-xs-2 text-center">{{ trans('transaction.spending_total') }}</td>
            <td class="col-xs-2 text-center">{{ trans('transaction.difference') }}</td>
        </tr>
        <tr>
            <td class="text-center lead" style="border-top: none;">{{ number_format($incomeTotal, 2) }}</td>
            <td class="text-center lead" style="border-top: none;">{{ number_format($spendingTotal, 2) }}</td>
            <td class="text-center lead" style="border-top: none;">{{ number_format($incomeTotal - $spendingTotal, 2) }}</td>
        </tr>
    </table>
</div>
