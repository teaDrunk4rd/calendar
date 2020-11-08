<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $model)
    {
        return $user->id === $model->id
            ? Response::allow()
            : Response::deny('Вы не можете просматривать данного пользователя');
    }

    public function update(User $user, User $model)
    {
        return $user->id === $model->id
            ? Response::allow()
            : Response::deny('Вы не можете редактировать данного пользователя');
    }
}
