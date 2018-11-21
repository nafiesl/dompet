<?php

namespace App\Http\Controllers;

use App\Partner;
use App\Transaction;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of the partner.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $editablePartner = null;
        $partnerQuery = Partner::query();
        $partnerQuery->where('name', 'like', '%'.request('q').'%');
        $partners = $partnerQuery->paginate(25);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editablePartner = Partner::find(request('id'));
        }

        return view('partners.index', compact('partners', 'editablePartner'));
    }

    /**
     * Store a newly created partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Partner);

        $newPartner = $request->validate([
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newPartner['creator_id'] = auth()->id();

        Partner::create($newPartner);

        return redirect()->route('partners.index');
    }

    /**
     * Show transaction listing of a partner.
     *
     * @param  \App\Partner  $partner
     * @return \Illuminate\View\View
     */
    public function show(Partner $partner)
    {
        $editableTransaction = null;
        $year = request('year', date('Y'));
        $categories = $this->getCategoryList();
        $startDate = request('start_date', date('Y-m').'-01');
        $endDate = request('end_date', date('Y-m-d'));
        $transactions = $this->getPartnerTransactions($partner, [
            'category_id' => request('category_id'),
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'query'       => request('query'),
        ]);
        $incomeTotal = $this->getIncomeTotal($transactions);
        $spendingTotal = $this->getSpendingTotal($transactions);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $partners = $this->getPartnerList();
            $editableTransaction = Transaction::find(request('id'));
        }

        return view('partners.show', compact(
            'partner', 'transactions', 'year', 'incomeTotal', 'spendingTotal',
            'startDate', 'endDate', 'categories', 'editableTransaction',
            'partners'
        ));
    }

    /**
     * Get transaction listing of a partner.
     *
     * @param  \App\Partner   $partner
     * @param  array  $criteria
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPartnerTransactions(Partner $partner, array $criteria)
    {
        $query = $criteria['query'];
        $endDate = $criteria['end_date'];
        $startDate = $criteria['start_date'];
        $categoryId = $criteria['category_id'];

        $transactionQuery = $partner->transactions();
        $transactionQuery->where('description', 'like', '%'.$query.'%');
        $transactionQuery->whereBetween('date', [$startDate, $endDate]);
        if ($categoryId) {
            $transactionQuery->where('category_id', $categoryId);
        }

        return $transactionQuery->orderBy('date', 'desc')->with('category')->get();
    }

    /**
     * Update the specified partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partner  $partner
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partnerData = $request->validate([
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $partner->update($partnerData);

        return redirect()->route('partners.index');
    }

    /**
     * Remove the specified partner from storage.
     *
     * @param  \App\Partner  $partner
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Partner $partner)
    {
        $this->authorize('delete', $partner);

        request()->validate([
            'partner_id' => 'required',
        ]);

        if (request('partner_id') == $partner->id && $partner->delete()) {
            return redirect()->route('partners.index');
        }

        return back();
    }
}
