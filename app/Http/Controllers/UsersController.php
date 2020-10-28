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
            'email' => 'required|max:255',
            'password' => 'max:255',
        ]);

        $user = User::find($request["id"]);
        if ($user) {
            $user->full_name = $request['full_name'] == null ? '' : $request['full_name'];

            if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL))
                return response()->json(['message' => 'E-mail введен неверно']);

            if ($user->email != $request['email']) {
                if (User::where('email', $request['email'])->first() == null)
                    $user->email = $request['email'];
                else
                    return response()->json(['message' => 'Пользователь с таким email уже существует']);
            }

            if ($request['changePassword'] == 'true') {
                if (Hash::check($request['oldPassword'], $user->password))
                    $user->password = Hash::make($request['password']);
                else
                    return response()->json(['message' => 'Неверный старый пароль']);
            }

            $user->save();
            return response()->json($user, 200);
        }

        return response()->json(['message' => 'Не найден пользователь']);
    }
}
