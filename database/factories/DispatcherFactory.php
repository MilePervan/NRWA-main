<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dispatcher;
use App\Models\Users;

class DispatcherFactory extends Factory
{
    protected $model = Dispatcher::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'user_id' => Users::inRandomOrder()->first()->id,
        ];
    }
}
