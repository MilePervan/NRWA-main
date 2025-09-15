<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dispatcher;
use App\Models\Users;

class DispatcherSeeder extends Seeder
{
    public function run(): void
    {
        $users = Users::all();

        Dispatcher::factory(10)->create()->each(function($dispatcher) use ($users) {
            $dispatcher->user_id = $users->random()->id;
            $dispatcher->save();
        });
    }
}
