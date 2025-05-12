<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('ru_RU');
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s') . ' +2 days');

        return [
            'name' => $faker->realText(20),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => $faker->realText(200),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
