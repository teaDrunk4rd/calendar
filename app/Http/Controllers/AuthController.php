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
            'email' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        if (!User::where('email', $request['email'])->first() == null)
            return response()->json(['message' => 'Пользователь с таким email уже существует']);

        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL))
            return response()->json(['message' => 'E-mail введен неверно']);

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
        $this->validate($request, [
            'email' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL))
            return response()->json(['message' => 'E-mail введен неверно']);

        $user = User::where('email', $request['email'])->first();
        if ($user != null && Hash::check($request['password'], $user->password))
            return response()->json([
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email']
            ], 200);

        return response()->json(['message' => 'Неверный логин или пароль']);
    }
}
