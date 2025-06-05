<?php

namespace Database\Factories;

use App\Models\Catalog;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogFactory extends Factory
{
    protected $model = Catalog::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'cover' => null,
            'release_year' => $this->faker->year,

        ];
    }
}

