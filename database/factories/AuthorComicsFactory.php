<?php

namespace Database\Factories;

use App\Models\AuthorComics;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AuthorComicsFactory extends Factory
{
    protected $model = AuthorComics::class;

    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        return [
            'created_by' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name . '-' . $this->faker->unique()->numberBetween(1, 1000)),
            'description' => $this->faker->paragraph,
            'views' => $this->faker->numberBetween(0, 1000),
            'cover' => $this->faker->imageUrl(),
            'comics_file' => $this->faker->filePath(),
            'age_restriction' => $this->faker->numberBetween(0, 18),
            'average_assessment' => $this->faker->numberBetween(0, 5),
            'is_moderated' => $this->faker->randomElement(['successful', 'unsuccessful', 'under review']),
            'is_published' => $this->faker->boolean,
            'published_at' => $this->faker->boolean ? Carbon::now() : null,
            'feedback' => $this->faker->optional()->sentence,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}