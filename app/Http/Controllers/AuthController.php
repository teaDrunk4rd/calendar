<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function registration(RegistrationRequest $request)
    {
        $validatedRequest = $request->validated();
        $validatedRequest['password'] = Hash::make($validatedRequest['password']);
        $user = $this->user->create($validatedRequest);

        return response()->json($user, 201)
            ->withCookie('token', $this::getToken($user), config('jwt.ttl'), "/", null, false, true);
    }

    public function login(LoginRequest $request)
    {
        $validatedRequest = $request->validated();
        $user = $this->user->where('email', $validatedRequest['email'])->first();

        return response()->json($user, 200)
            ->withCookie('token', $this::getToken($user), config('jwt.ttl'), "/", null, false, true);
    }

    public function logout()
    {
        $cookie = Cookie::forget('token');
        return response()->json()->withCookie($cookie);
    }

    private function getToken($user)
    {
        $factory = JWTFactory::customClaims([
            'iss' => config('app.name'),
            'iat' => Carbon::now()->timestamp,
            'nbf' => Carbon::now()->timestamp,
            'sub' => $user->id,
            'jti' => uniqid(),

            'name' => $user->email,
            'csrf-token' => Str::random(32),
        ]);
        $payload = $factory->make();
        return JWTAuth::encode($payload);
    }
}
