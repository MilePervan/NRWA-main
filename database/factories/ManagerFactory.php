<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Manager;
use App\Models\Location;

class ManagerFactory extends Factory
{
    protected $model = Manager::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'location_id' => Location::inRandomOrder()->first()->id,
        ];
    }
}
