<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthTokenController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'account' => 'required',
            'password' => 'required',
        ]);

        /**
         * @var $user User
         */
        $user = User::where('name', $request->account)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'account' => [trans('auth.failed')],
            ]);
        }

        return [
            'access_token' => $user->createToken('backstage')->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }

    public function logout(Request $request)
    {
        /**
         * @var $user User
         */
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->noContent();

    }
}
