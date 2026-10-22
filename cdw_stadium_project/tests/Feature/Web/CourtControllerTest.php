<?php

namespace Tests\Feature\Web;

use App\Models\Court;
use App\Models\Facility;
use App\Models\SportType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourtControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_courts()
    {
        $sportType = SportType::factory()->create(['status' => 1]);
        $facility = Facility::factory()->create(['status' => 1, 'district' => 'District 1']);
        $court = Court::factory()->create([
            'sport_type_id' => $sportType->id,
            'facility_id' => $facility->id,
            'status' => 'available'
        ]);

        $response = $this->get(route('courts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('web.courts.index');
        $response->assertViewHas('courts');
    }

    public function test_index_filters_by_sport_types()
    {
        $sportType1 = SportType::factory()->create(['status' => 1]);
        $sportType2 = SportType::factory()->create(['status' => 1]);
        $facility = Facility::factory()->create(['status' => 1]);

        $court1 = Court::factory()->create(['sport_type_id' => $sportType1->id, 'facility_id' => $facility->id, 'status' => 'available']);
        $court2 = Court::factory()->create(['sport_type_id' => $sportType2->id, 'facility_id' => $facility->id, 'status' => 'available']);

        $response = $this->get(route('courts.index', ['sport_types' => [$sportType1->id]]));

        $response->assertStatus(200);
        $response->assertViewIs('web.courts.index');

        $courts = $response->original->getData()['courts'];
        $this->assertTrue($courts->contains($court1));
        $this->assertFalse($courts->contains($court2));
    }

    public function test_show_displays_court_details()
    {
        $court = Court::factory()->create();

        $response = $this->get(route('courts.show', $court));

        $response->assertStatus(200);
        $response->assertViewIs('web.courts.show');
        $response->assertViewHas('court');
    }
}
