<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'full_name' => 'max:255',
            'email' => 'required|max:255|email|unique:users',
            'password' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'full_name.max' => 'Слишком длинное имя',
            'email.required' => 'Заполните email',
            'email.max' => 'Слишком длинный email',
            'email.email' => 'E-mail введен неверно',
            'email.unique' => 'Пользователь с таким email уже существует',
            'password.required' => 'Заполните пароль',
            'password.max' => 'Слишком длинный пароль'
        ];
    }
}
