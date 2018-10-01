<?php

namespace App\Http\Controllers;

use App\Partner;
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

        $routeParam = request()->only('page', 'q');

        return redirect()->route('partners.index', $routeParam);
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
            $routeParam = request()->only('page', 'q');

            return redirect()->route('partners.index', $routeParam);
        }

        return back();
    }
}
