<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Token;
use Illuminate\Session\TokenMismatchException;

class JWTAuthenticate
{
    public function handle($request, Closure $next)
    {
        try {
            if(!$request->headers->has('csrf-token')) throw new TokenMismatchException();

            $rawToken = $request->cookie('token');
            $token = new Token($rawToken);
            $payload = JWTAuth::decode($token);
            if($payload['csrf-token'] != $request->headers->get('csrf-token')) throw new TokenMismatchException();
            Auth::loginUsingId($payload['sub']);
        } catch(\Exception $e) {
            if ( $e instanceof TokenExpiredException) {
                // refresh token
            }
            return response()->json('Unauthorized', 401);
        }

        return $next($request);
    }
}
