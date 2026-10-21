<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement(['created_booking', 'updated_profile', 'logged_in', 'logged_out', 'deleted_review']),
            'description' => fake('vi_VN')->sentence(),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
