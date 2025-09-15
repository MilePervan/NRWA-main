<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manager;
use App\Models\Dispatcher;
use App\Models\Location;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        $locations = Location::all();
        $dispatchers = Dispatcher::all();

        Manager::factory(8)->create()->each(function($manager) use ($locations, $dispatchers) {
            $manager->location_id = $locations->random()->id;
            $manager->save();

            $assignedDispatchers = $dispatchers->random(rand(1,3))->pluck('id')->toArray();
            $manager->dispatchers()->sync($assignedDispatchers);
        });
    }
}
