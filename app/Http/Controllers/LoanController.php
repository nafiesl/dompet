<?php

namespace App\Http\Controllers;

use App\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the loan.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $loanQuery = Loan::query();
        if ($request->get('q')) {
            $loanQuery->where('description', 'like', '%'.$request->get('q').'%');
        }
        if ($request->get('type_id')) {
            $loanQuery->where('type_id', 'like', '%'.$request->get('type_id').'%');
        }
        $loanQuery;
        $loans = $loanQuery->with(['partner'])->latest()->paginate(25);

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
        $partners = $this->getPartnerList();
        $loanTypes = [
            Loan::TYPE_DEBT       => __('loan.types.debt'),
            Loan::TYPE_RECEIVABLE => __('loan.types.receivable'),
        ];

        return view('loans.create', compact('partners', 'loanTypes'));
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
            'partner_id'            => 'required|exists:partners,id',
            'type_id'               => 'required|in:'.Loan::TYPE_DEBT.','.Loan::TYPE_RECEIVABLE,
            'amount'                => 'required|numeric',
            'planned_payment_count' => 'required|numeric',
            'description'           => 'nullable|max:255',
            'start_date'            => 'nullable|date_format:Y-m-d',
            'end_date'              => 'nullable|date_format:Y-m-d',
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
        $partners = $this->getPartnerList();
        $loanTypes = [
            Loan::TYPE_DEBT       => __('loan.types.debt'),
            Loan::TYPE_RECEIVABLE => __('loan.types.receivable'),
        ];

        return view('loans.edit', compact('loan', 'partners', 'loanTypes'));
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
            'partner_id'            => 'required|exists:partners,id',
            'type_id'               => 'required|in:'.Loan::TYPE_DEBT.','.Loan::TYPE_RECEIVABLE,
            'amount'                => 'required|numeric',
            'planned_payment_count' => 'required|numeric',
            'description'           => 'nullable|max:255',
            'start_date'            => 'nullable|date_format:Y-m-d',
            'end_date'              => 'nullable|date_format:Y-m-d',
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
