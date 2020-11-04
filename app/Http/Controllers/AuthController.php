<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

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

        return response()->json($user, 201)
            ->withCookie('token', $this::getToken($user), config('jwt.ttl'), "/", null, false, true);
    }

    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL))
            return response()->json(['message' => 'E-mail введен неверно']);

        $user = User::where('email', $request['email'])->first();
        if ($user == null || !Hash::check($request['password'], $user->password))
            return response()->json(['message' => 'Неверный логин или пароль']);


        return response()->json([
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email']
        ], 200)->withCookie('token', $this::getToken($user), config('jwt.ttl'), "/", null, false, true);
    }

    public function logout() {
        $cookie = Cookie::forget('token');
        return response()->json()->withCookie($cookie);
    }

    private function getToken($user) {
        $factory = JWTFactory::customClaims([
            'iss'   => config('app.name'),
            'iat'   => Carbon::now()->timestamp,
            'nbf'   => Carbon::now()->timestamp,
            'sub'   => $user->id,
            'jti'   => uniqid(),

            'name'=> $user->email,
            'csrf-token' => Str::random(32),
        ]);
        $payload = $factory->make();
        return JWTAuth::encode($payload);
    }
}
