<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'unique:App\Models\User,username',
            'password' => [Password::required(), Password::min(4)->numbers()/*->mixedCase()->letters()->symbols()->uncompromised()*/, 'confirmed'],
            'image' => 'image',
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->image = $request->image ?? '#';
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'You have successfully registered, utilize your username and password to log in']);

    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

//      $user = User::query()->where('username', $request->username)->where('password', $request->password)->firstOrFail();

        if (!$user = User::query()->where('username', $request->username)->first()) {
            return response()->json([
                "message" => "User not found"
            ]);
        }

        $pass_check = Hash::check($request->password, User::query()->where('username', $request->username)->firstOrFail()->password);

        if ($user && $pass_check) {
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('token_base_name')->plainTextToken
            ]);
        } else {
            return response()->json(['message' => 'Your username or password is incorrect']);
        }

    }


    public function logout()
    {
        /** @var User $user */
        $user = auth()->user();

        $user->tokens()->delete();

        return \response()->json('logged out');
    }


    public function changePass(Request $request)
    {
        $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required',
        ]);
        $pass_check = Hash::check($request->old_pass, User::query()->where('id', '=', auth()->id())->firstOrFail()->password);
        if ($pass_check) {
            User::query()->where('id', '=', auth()->id())->update([
                'password' => Hash::make($request->new_pass)
            ]);
            return $this->response(1,'password changed to ' . $request->new_pass);
        } else {
            return $this->response(0,'token ERROR');
        }
    }

}











