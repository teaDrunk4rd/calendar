<?php

namespace App\Providers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Validator::extend('check_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, $parameters[0]);
        });
        Validator::extend('check_password_if', function ($attribute, $value, $parameters, $validator) {
            var_dump($parameters[0]);
            return $parameters[0] == 'true' || Hash::check($value, $parameters[1]);
//            $data = $validator->getData();
//            return $data['changePasswordFlag'] == 'true' || Hash::check($value, $parameters[3]);
        });
    }
}
