<?php

namespace App\Policies;

use App\Models\Dispatcher;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class DispatcherPolicy
{
    public function viewAny(Users $user)
    {
        return in_array($user->role, ['guest', 'reader', 'editor', 'admin']);
    }

    public function view(Users $user, Dispatcher $dispatcher)
    {
        return in_array($user->role, ['guest', 'reader', 'editor', 'admin']);
    }

    public function create(Users $user)
    {
        return in_array($user->role, ['editor', 'admin']);
    }

    public function update(Users $user, Dispatcher $dispatcher)
    {
        return in_array($user->role, ['editor', 'admin']);
    }

    public function delete(Users $user, Dispatcher $dispatcher)
    {
        return in_array($user->role, ['editor', 'admin']);
    }
}