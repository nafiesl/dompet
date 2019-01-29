<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Show a profile user.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = auth()->user();

        return view('auth.profile.show', compact('user'));
    }
}
