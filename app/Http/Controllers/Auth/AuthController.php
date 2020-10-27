<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $this->validate($request, User::$validation);

        User::create([
            'full_name' => $request['full_name'],
            'email' => $request['full_name'],
            'password' => Hash::make($request['password']),
            'remember_token' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return response()->json(null, 201);
    }

    public function login(Request $request) {
        $this->validate($request, User::$validation);

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
