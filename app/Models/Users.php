<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function dispatcher()
    {
        return $this->hasOne(Dispatcher::class, 'user_id', 'id');
    }


    public function manager()
    {
        return $this->hasOne(Manager::class, 'user_id', 'id');
    }
}
