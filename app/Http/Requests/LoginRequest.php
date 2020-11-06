<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|max:255|email|exists:users,email',
            'password' => 'required|max:255|check_password:'. User::where('email', $this->email)->first()->password,
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Заполните email',
            'email.max' => 'Слишком длинный email',
            'email.email' => 'E-mail введен неверно',
            'email.exists' => 'Неверный email или пароль',
            'password.required' => 'Заполните пароль',
            'password.max' => 'Слишком длинный пароль',
            'password.check_password' => 'Неверный email или пароль'
        ];
    }
}
