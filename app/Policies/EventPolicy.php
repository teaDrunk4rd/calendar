<?php

namespace App\Policies;

use App\Event;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Event $event)
    {
        return $event->creator_id === $user->id
            ? Response::allow()
            : Response::deny('Вы не можете редактировать данное событие');
    }
}
