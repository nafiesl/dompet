<?php

namespace App\Http\Controllers;

use App\Category;
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

    public function show(Category $category)
    {
        $year = request('year', date('Y'));
        $transactions = $this->getCategoryTransactions($category, $year, request('query'));
        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        return view('categories.show', compact(
            'category', 'transactions', 'year', 'incomeTotal', 'spendingTotal'
        ));
    }

    /**
     * Get transaction listing of a category
     *
     * @param  \App\Category   $category
     * @param  string  $year
     * @param  string|null  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getCategoryTransactions(Category $category, string $year, string $query = null)
    {
        $transactionQuery = $category->transactions();
        $transactionQuery->where('description', 'like', '%'.$query.'%');
        $transactionQuery->whereYear('created_at', $year);

        return $transactionQuery->latest()->get();
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
