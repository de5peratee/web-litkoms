<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('ru_RU');

        return [
            'name' => $faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
