<?php

namespace App\Http\Controllers\Api\Auth;

use Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'    => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);
    }

    public function login(Request $request)
    {
        $this->validator($request->all())->validate();

        $form = [
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username' => $request->email,
            'password' => $request->password,
        ];

        $request->request->add($form);

        $requestToken = Request::create('oauth/token', 'POST');
        $response = Route::dispatch($requestToken);

        return response()->json(json_decode((string) $response->content(), true), $response->status());
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->revoke();
        });

        return response()->json(array(
            "message" => 'Logged out successfully'
        ), 200);
    }
}
