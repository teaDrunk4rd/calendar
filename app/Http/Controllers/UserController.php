<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show()
    {
        return response()->json(Auth::user(), 200);
    }

    public function update(UserUpdateRequest $request)
    {
        $request = $request->validated();
        $user = $this->user::find(Auth::user()->id);

        $request['full_name'] = $request['full_name'] != null ? $request['full_name'] : '';
        $request['password'] = $request['password'] != null && $request['password'] != ''
            ? Hash::make($request['password'])
            : $user->password;

        $user->update($request);

        return response()->json($user, 200);
    }
}
