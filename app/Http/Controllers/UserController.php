<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show($id) {
        return response()->json(User::find($id), 200);
    }

    public function update(UserUpdateRequest $request) {
        $validatedRequest = $request->validated();

        $user = User::find($validatedRequest['id']);

        $validatedRequest['full_name'] = $validatedRequest['full_name'] != null ? $validatedRequest['full_name'] : '';
        $validatedRequest['password'] = $validatedRequest['password'] != null && $validatedRequest['password'] != ''
            ? Hash::make($validatedRequest['password'])
            : $user->password;

        $user->update($validatedRequest);

        return response()->json(User::find($validatedRequest['id']), 200);
    }
}
