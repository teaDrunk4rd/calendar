<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function read($id) {
        return response()->json(User::find($id), 200);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'full_name' => 'max:255',
            'email' => 'required|max:255', // TODO: format
            'password' => 'max:255',
        ]);

        $user = User::find($request["id"]);
        if ($user) {
            if ($request['full_name'] != '')
                $user->full_name = $request['full_name'];

            if ($user->email != $request['email'] && User::where('email', $request['email'])->first() == null) // TODO: message
                $user->email = $request['email'];

            if ($request['password'] != '')
                $user->password = Hash::make($request['password']);

            $user->save();

            return response()->json($user, 200);
        }

        return response()->json($user, 404);
    }
}
