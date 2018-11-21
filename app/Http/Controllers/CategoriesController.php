<?php

namespace App\Http\Controllers;

use App\Category;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\Categories\CreateRequest;
use App\Http\Requests\Categories\DeleteRequest;
use App\Http\Requests\Categories\UpdateRequest;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the category.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $editableCategory = null;
        $categories = Category::all();

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableCategory = Category::find(request('id'));
        }

        return view('categories.index', compact('categories', 'editableCategory'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $categoryCreateForm)
    {
        $category = $categoryCreateForm->save();

        flash(__('category.created'), 'success');

        return redirect()->route('categories.index');
    }

    /**
     * Show transaction listing of a category.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {
        $year = request('year', date('Y'));
        $partners = $this->getPartnerList();
        $startDate = request('start_date', date('Y-m').'-01');
        $endDate = request('end_date', date('Y-m-d'));
        $transactions = $this->getCategoryTransactions($category, [
            'partner_id' => request('partner_id'),
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'query'      => request('query'),
        ]);
        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $categories = $this->getCategoryList();
            $editableTransaction = Transaction::find(request('id'));
        }

        return view('categories.show', compact(
            'category', 'transactions', 'year', 'incomeTotal', 'spendingTotal',
            'startDate', 'endDate', 'partners', 'editableTransaction', 'categories'
        ));
    }

    /**
     * Get transaction listing of a category.
     *
     * @param  \App\Category   $category
     * @param  array  $criteria
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getCategoryTransactions(Category $category, array $criteria)
    {
        $query = $criteria['query'];
        $endDate = $criteria['end_date'];
        $startDate = $criteria['start_date'];
        $partnerId = $criteria['partner_id'];

        $transactionQuery = $category->transactions();
        $transactionQuery->where('description', 'like', '%'.$query.'%');
        $transactionQuery->whereBetween('date', [$startDate, $endDate]);
        if ($partnerId) {
            $transactionQuery->where('partner_id', $partnerId);
        }

        return $transactionQuery->orderBy('date', 'desc')->with('partner')->get();
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \App\Http\Requests\Categories\UpdateRequest  $categoryUpdateForm
     * @param  \App\Category  $category
     * @return \Illuminate\Routing\Redirector
     */
    public function update(UpdateRequest $categoryUpdateForm, Category $category)
    {
        $category = $categoryUpdateForm->save();

        flash(__('category.updated'), 'success');

        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Http\Requests\Categories\DeleteRequest  $categoryDeleteForm
     * @param  \App\Category  $category
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(DeleteRequest $categoryDeleteForm, Category $category)
    {
        if ($categoryDeleteForm->delete()) {
            flash(__('category.deleted'), 'warning');

            return redirect()->route('categories.index');
        }

        flash(__('category.undeleted'), 'warning');

        return back();
    }
}
