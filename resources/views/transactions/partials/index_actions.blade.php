<div class="pull-right">
    @can('create', new App\Transaction)
        {{ link_to_route('transactions.index', __('transaction.add_income'), ['action' => 'add-income', 'month' => $month, 'year' => $year], ['class' => 'btn btn-success']) }}
        {{ link_to_route('transactions.index', __('transaction.add_spending'), ['action' => 'add-spending', 'month' => $month, 'year' => $year], ['class' => 'btn btn-danger']) }}
    @endcan
</div>
