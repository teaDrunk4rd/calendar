<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function read(Request $request) {
        return User::find($request['id']);
    }

    public function update(Request $request) {
        $this->validate($request, User::$validation);

        $user = User::find($request["id"]);
        $user->full_name = $request['full_name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();

        return response()->json($user, 200);
    }
}
