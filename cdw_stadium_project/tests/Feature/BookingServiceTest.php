<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\Facility;
use App\Models\SportType;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_price_performance()
    {
        // Setup data
        $sportType = SportType::create(['name' => 'Football']);
        $facility = Facility::create([
            'name' => 'Facility 1', 'district' => 'District 1', 'address' => 'Address 1', 'phone' => '1234567890',
            'open_time' => '06:00:00', 'close_time' => '23:00:00',
        ]);
        $court = Court::create([
            'facility_id' => $facility->id, 'sport_type_id' => $sportType->id,
            'name' => 'Court 1', 'status' => 'available',
        ]);

        // Create price rules
        DB::table('price_rules')->insert([
            ['sport_type_id' => $sportType->id, 'day_type' => 'weekday', 'start_time' => '06:00:00', 'end_time' => '10:00:00', 'price' => 100000, 'status' => 1, 'court_id' => null],
            ['sport_type_id' => $sportType->id, 'day_type' => 'weekday', 'start_time' => '10:00:00', 'end_time' => '14:00:00', 'price' => 150000, 'status' => 1, 'court_id' => null],
            ['sport_type_id' => $sportType->id, 'day_type' => 'weekday', 'start_time' => '14:00:00', 'end_time' => '18:00:00', 'price' => 200000, 'status' => 1, 'court_id' => null],
            ['sport_type_id' => $sportType->id, 'day_type' => 'weekday', 'start_time' => '18:00:00', 'end_time' => '22:00:00', 'price' => 250000, 'status' => 1, 'court_id' => null],
            ['sport_type_id' => $sportType->id, 'day_type' => 'weekday', 'start_time' => '22:00:00', 'end_time' => '23:00:00', 'price' => 100000, 'status' => 1, 'court_id' => null],
        ]);

        $service = new BookingService;

        // Warm up and simple correctness check
        $price = $service->calculatePrice($court, '2026-09-01', '06:00', '10:00'); // 4 hours at 100k
        $this->assertEquals(400000, $price);

        $price2 = $service->calculatePrice($court, '2026-09-01', '06:00', '22:00'); // Multiple rules
        // 06:00-10:00 = 400k
        // 10:00-14:00 = 600k
        // 14:00-18:00 = 800k
        // 18:00-22:00 = 1000k
        // Total: 2800000
        $this->assertEquals(2800000, $price2);

        // Benchmark
        $start = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            $service->calculatePrice($court, '2026-09-01', '06:00', '22:00'); // 16 iterations internally
        }
        $end = microtime(true);

        $time = ($end - $start) * 1000;
        echo "\nTime for 100 calculations (16 hours each): {$time} ms\n";
    }

    public function test_cancel_booking_success()
    {
        $user = User::factory()->create();

        $this->travelTo(Carbon::parse('2026-09-01 08:00:00'));

        $booking = Booking::factory()->create([
            'booking_date' => '2026-09-01',
            'start_time' => '12:00:00', // 4 hours in the future
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        $service = new BookingService();
        $service->cancel($booking, $user->id);

        $this->assertEquals('cancelled', $booking->fresh()->status);
        $this->assertDatabaseHas('booking_logs', [
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'action' => 'cancelled',
        ]);
    }

    public function test_cancel_booking_throws_exception_if_past_time_limit()
    {
        $user = User::factory()->create();

        $this->travelTo(Carbon::parse('2026-09-01 11:00:00'));

        $booking = Booking::factory()->create([
            'booking_date' => '2026-09-01',
            'start_time' => '12:00:00', // 1 hour in the future, past the 2 hours free cancel limit
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        $service = new BookingService();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Không thể hủy vì đã quá thời gian quy định.');

        $service->cancel($booking, $user->id);
    }
}
