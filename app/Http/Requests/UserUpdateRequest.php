<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'full_name' => 'max:255',
            'email' => 'required|max:255|email|unique:users,email,' . Auth::user()->id,
            'changePasswordFlag' => '',
            'password' => 'required_if:changePasswordFlag,==,true|max:255|confirmed',
            'password_confirmation' => 'required_if:changePasswordFlag,==,true',
            'oldPassword' => [
                'required_if:changePasswordFlag,==,true',
                "check_password_from_if:changePasswordFlag,true,id," . Auth::user()->id,
            ]
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
            'password.required_if' => 'Заполните новый пароль',
            'password.confirmed' => 'Новый пароль и подтверждение пароля должны совпадать',
            'password_confirmation.required_if' => 'Заполните подтверждение пароля',
            'password.max' => 'Слишком длинный пароль',
            'oldPassword.required_if' => 'Заполните старый пароль',
            'oldPassword.check_password_from_if' => 'Неверный старый пароль'
        ];
    }
}
