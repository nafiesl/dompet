<?php

namespace App\Http\Controllers;

use App\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the loan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $loanQuery = Loan::query();
        $loanQuery->where('name', 'like', '%'.request('q').'%');
        $loans = $loanQuery->paginate(25);

        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', new Loan);

        return view('loans.create');
    }

    /**
     * Store a newly created loan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Loan);

        $newLoan = $request->validate([
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newLoan['creator_id'] = auth()->id();

        $loan = Loan::create($newLoan);

        return redirect()->route('loans.show', $loan);
    }

    /**
     * Display the specified loan.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\View\View
     */
    public function show(Loan $loan)
    {
        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified loan.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\View\View
     */
    public function edit(Loan $loan)
    {
        $this->authorize('update', $loan);

        return view('loans.edit', compact('loan'));
    }

    /**
     * Update the specified loan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Loan $loan)
    {
        $this->authorize('update', $loan);

        $loanData = $request->validate([
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $loan->update($loanData);

        return redirect()->route('loans.show', $loan);
    }

    /**
     * Remove the specified loan from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Loan $loan)
    {
        $this->authorize('delete', $loan);

        $request->validate(['loan_id' => 'required']);

        if ($request->get('loan_id') == $loan->id && $loan->delete()) {
            return redirect()->route('loans.index');
        }

        return back();
    }
}
