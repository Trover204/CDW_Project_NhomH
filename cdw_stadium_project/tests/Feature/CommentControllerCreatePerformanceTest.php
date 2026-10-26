<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\Facility;
use App\Models\SportType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerCreatePerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_comment_performance()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(10000)->create();

        $facility = Facility::factory()->create();
        $sportType = SportType::factory()->create();

        $courts = [];
        for ($i = 0; $i < 1000; $i++) {
            $courts[] = [
                'facility_id' => $facility->id,
                'sport_type_id' => $sportType->id,
                'name' => 'Sân '.$i,
                'capacity' => 10,
                'description' => 'Mô tả',
                'status' => 'available',
                'version' => 1,
            ];
        }
        Court::insert($courts);

        $start = microtime(true);

        $response = $this->actingAs($admin)
            ->get(route('admin.comments.create'));

        $end = microtime(true);
        $time = $end - $start;

        $response->assertStatus(200);

        echo "Time taken for create (baseline with 10k users, 1k courts): {$time} seconds\n";
        $this->assertTrue(true);
    }
}
