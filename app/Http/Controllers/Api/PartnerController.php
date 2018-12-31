<?php

namespace App\Http\Controllers\Api;

use App\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PartnerController extends Controller
{
    /**
     * Get a listing of the partner.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Partner::all();
    }

    /**
     * Store a newly created partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Partner);

        $newPartner = $request->validate([
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newPartner['creator_id'] = auth()->id();

        $partner = Partner::create($newPartner);

        return response()->json([
            'message' => __('partner.created'),
            'data'    => $partner,
        ], 201);
    }

    /**
     * Update the specified partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $partnerData = $request->validate([
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $partner->update($partnerData);

        return response()->json([
            'message' => __('partner.updated'),
            'data'    => $partner,
        ]);
    }

    /**
     * Remove the specified partner from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Partner $partner)
    {
        $this->authorize('delete', $partner);

        request()->validate([
            'partner_id' => 'required',
        ]);

        if (request('partner_id') == $partner->id && $partner->delete()) {
            return response()->json(['message' => __('partner.deleted')]);
        }

        return response()->json('Unprocessable Entity.', 422);
    }
}
