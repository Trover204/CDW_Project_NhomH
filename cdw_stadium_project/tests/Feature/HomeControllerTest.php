<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\Facility;
use App\Models\SportType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_home_page_with_correct_data(): void
    {
        // Seed active sport types
        $activeSportType1 = SportType::factory()->create(['status' => 1]);
        $activeSportType2 = SportType::factory()->create(['status' => 1]);
        $activeSportType3 = SportType::factory()->create(['status' => 1]);
        // Sport type that should not be visible or fetched if over limit/inactive
        $inactiveSportType = SportType::factory()->create(['status' => 0]);
        $extraActiveSportType = SportType::factory()->create(['status' => 1]);

        // Seed active facilities
        $facility1 = Facility::factory()->create(['status' => 1, 'district' => 'District A']);
        $facility2 = Facility::factory()->create(['status' => 1, 'district' => 'District B']);
        $facility3 = Facility::factory()->create(['status' => 1, 'district' => 'District C']);
        // Extra facility to test distinct
        $facility4 = Facility::factory()->create(['status' => 1, 'district' => 'District C']); // Duplicate district
        $facility5 = Facility::factory()->create(['status' => 1, 'district' => 'District D']); // 4th distinct district
        // Inactive facility
        $inactiveFacility = Facility::factory()->create(['status' => 0, 'district' => 'District E']);
        // Controller gets facilities whereNotNull('district')
        // We can't create one with district => null because it is NOT NULL in the database!
        // We will just verify taking only status = 1

        // Seed courts with relationships
        $court1 = Court::factory()->create(['facility_id' => $facility1->id, 'sport_type_id' => $activeSportType1->id]);
        $court2 = Court::factory()->create(['facility_id' => $facility2->id, 'sport_type_id' => $activeSportType2->id]);
        $court3 = Court::factory()->create(['facility_id' => $facility3->id, 'sport_type_id' => $activeSportType3->id]);
        $court4 = Court::factory()->create(['facility_id' => $facility1->id, 'sport_type_id' => $activeSportType1->id]);
        $court5 = Court::factory()->create(['facility_id' => $facility2->id, 'sport_type_id' => $activeSportType2->id]); // 5th court, should not be included due to take(4)

        // Make a GET request to the home page
        $response = $this->get('/');

        // Assert HTTP status 200 and correct view is returned
        $response->assertStatus(200);
        $response->assertViewIs('web.home');

        // Assert that 'courts', 'sportTypes', and 'locations' are passed to the view
        $response->assertViewHasAll(['courts', 'sportTypes', 'locations']);

        // Retrieve data passed to the view
        $courtsData = $response->original->gatherData()['courts'];
        $sportTypesData = $response->original->gatherData()['sportTypes'];
        $locationsData = $response->original->gatherData()['locations'];

        // Assert Courts
        $this->assertCount(4, $courtsData);
        // Courts should be sorted by sport_type_id ascending
        $sortedCourtIds = $courtsData->pluck('id')->toArray();
        $this->assertEquals(
            [$court1->id, $court4->id, $court2->id, $court5->id], // explicitly listing the expected sorted order
            $sortedCourtIds
        );

        // Assert SportTypes
        $this->assertCount(3, $sportTypesData);
        $this->assertTrue($sportTypesData->contains('id', $activeSportType1->id));
        $this->assertTrue($sportTypesData->contains('id', $activeSportType2->id));
        $this->assertTrue($sportTypesData->contains('id', $activeSportType3->id));
        $this->assertFalse($sportTypesData->contains('id', $inactiveSportType->id));

        // Assert Locations (Facilities)
        // Controller gets all active, distinct non-null districts. No take() limit.
        $this->assertCount(4, $locationsData);
        $this->assertTrue($locationsData->contains('district', 'District A'));
        $this->assertTrue($locationsData->contains('district', 'District B'));
        $this->assertTrue($locationsData->contains('district', 'District C'));
        $this->assertTrue($locationsData->contains('district', 'District D'));
        $this->assertFalse($locationsData->contains('district', 'District E')); // Inactive district should not be present
    }
}
