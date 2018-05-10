<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;

class ProfileController extends Controller
{
    protected function show()
    {
        return new UserResource(auth()->user());
    }
}
