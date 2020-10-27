<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registration(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'max:255',
            'email' => 'required|unique:users|max:255', // TODO: format
            'password' => 'required|max:255',
        ]);

        $user = User::create([
            'full_name' => $request['full_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'remember_token' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $user->full_name = $request['full_name'];
        $user->save();

        return response()->json($user, 201);
    }

    public function login(Request $request) {

        $user = User::where('email', $request['email'])->first();
        if ($user != null && Hash::check($request['password'], $user->password))
            return response()->json([
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email']
            ], 200);

        return response()->json(null, 401);
    }
}
