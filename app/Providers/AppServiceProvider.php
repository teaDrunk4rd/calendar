<?php

namespace App\Providers;

use App\Event;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(User $user)
    {
        Validator::extend('check_password_from', function ($attribute, $value, $parameters, $validator) use ($user) {
            $user = $user::where($parameters[0], $parameters[1])->first();
            return $user && Hash::check($value, $user->password);
        });
        Validator::extend('check_password_from_if', function ($attribute, $value, $parameters, $validator) use ($user) {
            $data = $validator->getData();
            $user = $user::where($parameters[2], $parameters[3])->first();
            return $data[$parameters[0]] != $parameters[1] || ($user && Hash::check($value, $user->password));
        });

        $router = $this->app['router'];
        $router->bind('event', function ($value) {
            return Event::with('eventType')->find($value);
        });
    }
}
