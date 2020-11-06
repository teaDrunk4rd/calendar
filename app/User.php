<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;
    use Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function events()
    {
        return $this->hasMany('App\Event', 'id', 'creator_id');
    }
}
