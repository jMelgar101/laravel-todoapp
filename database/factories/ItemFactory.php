<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Checklist;
use App\Models\Item;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $isComplete = rand(0, 1);
        $completedAt = ($isComplete === 1) ? now() : null;

        return [
            'name' => fake()->sentence(rand(2, 3)),
            'to_complete_by_date' => fake()->dateTimeBetween('now', '+10 months'),
            'to_complete_by_time' => fake()->time('H:i'),
            'is_complete' => rand(0, 1),
            'completed_at' => $completedAt,
        ];
    }
}
