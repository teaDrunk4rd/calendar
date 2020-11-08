<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => "required|check_password_from:email,{$this->email}"
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Заполните email',
            'email.email' => 'E-mail введен неверно',
            'email.exists' => 'Неверный email или пароль',
            'password.required' => 'Заполните пароль',
            'password.check_password_from' => 'Неверный email или пароль'
        ];
    }
}
