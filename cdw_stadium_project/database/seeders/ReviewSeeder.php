<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->where('role', 'customer')->pluck('id')->toArray();
        $courts = DB::table('courts')->pluck('id')->toArray();

        if (empty($users) || empty($courts)) {
            return;
        }

        // We already seed reviews for completed bookings in BookingSeeder.
        // Let's add some more standalone reviews for variety.

        for ($i = 0; $i < 40; $i++) {
            Review::factory()->create([
                'user_id' => $users[array_rand($users)],
                'court_id' => $courts[array_rand($courts)],
                'booking_id' => null,
            ]);
        }
    }
}
