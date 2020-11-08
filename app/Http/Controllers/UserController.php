<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show(User $user)
    {
        $this->authorize('update', $user);
        return response()->json($user, 200);
    }

    public function update(UserUpdateRequest $request)
    {
        $request = $request->validated();
        $user = $this->user::find($request['id']);
        $this->authorize('update', $user);

        $request['full_name'] = $request['full_name'] != null ? $request['full_name'] : '';
        $request['password'] = $request['password'] != null && $request['password'] != ''
            ? Hash::make($request['password'])
            : $user->password;

        $user->update($request);

        return response()->json($this->user::find($request['id']), 200);
    }
}
