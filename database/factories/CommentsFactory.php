<?php

namespace Database\Factories;

use App\Models\Comments;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommentsFactory extends Factory
{
    protected $model = Comments::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $this->faker->randomNumber(),
            'comics_to' => $this->faker->randomNumber(),
            'comment' => $this->faker->word(),
        ];
    }
}
