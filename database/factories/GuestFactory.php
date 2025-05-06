<?php

namespace Database\Factories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
{
    protected $model = Guest::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('ru_RU');

        return [
            'name' => $faker->firstName(),
            'surname' => $faker->lastName(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
