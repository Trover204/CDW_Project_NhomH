<?php

namespace Tests\Feature\Web;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\Payment;
use App\Models\SportType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $court;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $sportType = SportType::factory()->create();
        $facility = Facility::factory()->create();

        $this->court = Court::factory()->create([
            'sport_type_id' => $sportType->id,
            'facility_id' => $facility->id,
        ]);
    }

    public function test_create_shows_booking_form()
    {
        $response = $this->actingAs($this->user)->get(route('bookings.create', $this->court));

        $response->assertStatus(200);
        $response->assertViewIs('web.bookings.create');
        $response->assertViewHas('court');
    }

    public function test_store_creates_booking_and_payment_successfully()
    {
        $bookingDate = Carbon::tomorrow()->toDateString();
        $startTime = '08:00';
        $endTime = '10:00';

        $payload = [
            'customer_name' => 'John Doe',
            'customer_phone' => '0123456789',
            'customer_email' => 'johndoe@example.com',
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'payment_method' => 'cod',
            'note' => 'Please prepare a clean court',
        ];

        $response = $this->actingAs($this->user)->post(route('bookings.store', $this->court), $payload);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Đặt sân thành công!');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->user->id,
            'court_id' => $this->court->id,
            'customer_name' => 'John Doe',
            'customer_phone' => '0123456789',
            'customer_email' => 'johndoe@example.com',
            'booking_date' => $bookingDate,
            'start_time' => "$startTime",
            'end_time' => "$endTime",
            'subtotal' => 240000,
            'total_price' => 240000,
            'note' => 'Please prepare a clean court',
            'status' => 'pending',
        ]);

        $booking = Booking::where('user_id', $this->user->id)->where('court_id', $this->court->id)->first();
        $this->assertNotNull($booking);

        $this->assertDatabaseHas('payments', [
            'payable_type' => $booking->getMorphClass(),
            'payable_id' => $booking->id,
            'user_id' => $this->user->id,
            'amount' => 240000,
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);
    }

    public function test_store_validation_failures()
    {
        $payload = [
            'customer_name' => '', // missing
            'customer_phone' => '123',
            'customer_email' => 'not-an-email',
            'booking_date' => Carbon::yesterday()->toDateString(), // past date
            'start_time' => '10:00',
            'end_time' => '08:00', // before start_time
        ];

        $response = $this->actingAs($this->user)->post(route('bookings.store', $this->court), $payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'customer_name',
            'customer_email',
            'booking_date',
            'end_time',
        ]);

        $this->assertDatabaseCount('bookings', 0);
    }
}
