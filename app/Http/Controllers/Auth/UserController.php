<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::query()->where('username', $request->username)->where('password', $request->password)->firstOrFail();

        if ($user) {
            return $user->createToken('token_base_name')->plainTextToken;
        }

    }


    public function logout()
    {
        \request()->user()->tokens()->delete();
        return response([], 204);
    }


}











