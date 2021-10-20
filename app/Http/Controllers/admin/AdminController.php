<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'unique:App\Models\User,username'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->image = '#';
        $user->password =  Hash::make('1234'); //default pass
        $user->save();

        $user->roles()->attach($request->role);

        return response()->json(['message' => 'User created successfully', 'Role' => $request->role]);
    }

    public function update(User $user, Request $request)
    {
        $request->validate([ 'role' => 'required', ]);

        if($user->roles()->where('role_id',$request->role)->exists())
        {
            return response()->json(['message' => "Role name already exists as a user"]);
        }

        $user->roles()->attach($request->role);
        return response()->json(['message' => 'The role was successfully created for the user']);
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();
        return response()->json(['message' => 'The role was successfully removed from the user']);
    }

}
