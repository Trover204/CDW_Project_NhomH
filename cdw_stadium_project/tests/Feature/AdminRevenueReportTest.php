<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\SportType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRevenueReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Facility $facility;

    protected SportType $football;

    protected SportType $badminton;

    protected Court $footballCourt;

    protected Court $badmintonCourt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->facility = Facility::factory()->create(['name' => 'Cơ sở Thủ Đức']);
        $this->football = SportType::factory()->create(['name' => 'Bóng đá mini']);
        $this->badminton = SportType::factory()->create(['name' => 'Cầu lông']);

        $this->footballCourt = Court::factory()->create([
            'facility_id' => $this->facility->id,
            'sport_type_id' => $this->football->id,
            'name' => 'Sân bóng đá 1',
        ]);

        $this->badmintonCourt = Court::factory()->create([
            'facility_id' => $this->facility->id,
            'sport_type_id' => $this->badminton->id,
            'name' => 'Sân cầu lông 1',
        ]);
    }

    public function test_admin_can_view_revenue_report_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.revenue'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.report');
        $response->assertSee('Báo cáo doanh thu');
        $response->assertSee('Tổng doanh thu');
        $response->assertSee('Số đơn hoàn thành');
        $response->assertSee('Giá trị đơn TB');
    }

    public function test_revenue_report_calculates_monthly_kpis(): void
    {
        // 2 completed bookings in October 2026
        Booking::factory()->create([
            'court_id' => $this->footballCourt->id,
            'booking_date' => '2026-10-05',
            'total_price' => 300000,
            'status' => 'completed',
        ]);

        Booking::factory()->create([
            'court_id' => $this->badmintonCourt->id,
            'booking_date' => '2026-10-15',
            'total_price' => 200000,
            'status' => 'completed',
        ]);

        // 1 cancelled booking in October 2026 (should not be counted in revenue)
        Booking::factory()->create([
            'court_id' => $this->footballCourt->id,
            'booking_date' => '2026-10-20',
            'total_price' => 150000,
            'status' => 'cancelled',
        ]);

        // 1 completed booking in September 2026 (different month)
        Booking::factory()->create([
            'court_id' => $this->footballCourt->id,
            'booking_date' => '2026-09-10',
            'total_price' => 400000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.revenue', [
            'month' => '2026-10',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue', 500000.0);
        $response->assertViewHas('completedBookingsCount', 2);
        $response->assertViewHas('averageOrderValue', 250000.0);
    }

    public function test_revenue_report_calculates_growth_rate_compared_to_previous_month(): void
    {
        // September: 400,000
        Booking::factory()->create([
            'court_id' => $this->footballCourt->id,
            'booking_date' => '2026-09-15',
            'total_price' => 400000,
            'status' => 'completed',
        ]);

        // October: 600,000 (growth: +50%)
        Booking::factory()->create([
            'court_id' => $this->footballCourt->id,
            'booking_date' => '2026-10-15',
            'total_price' => 600000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.revenue', [
            'month' => '2026-10',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('growthRate', 50.0);
    }

    public function test_revenue_report_filters_by_sport_type(): void
    {
        Booking::factory()->create([
            'court_id' => $this->footballCourt->id,
            'booking_date' => '2026-10-10',
            'total_price' => 300000,
            'status' => 'completed',
        ]);

        Booking::factory()->create([
            'court_id' => $this->badmintonCourt->id,
            'booking_date' => '2026-10-12',
            'total_price' => 200000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.revenue', [
            'month' => '2026-10',
            'sport_type_id' => $this->football->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue', 300000.0);
        $response->assertViewHas('completedBookingsCount', 1);
    }

    public function test_revenue_report_exports_excel_csv(): void
    {
        Booking::factory()->create([
            'court_id' => $this->footballCourt->id,
            'booking_date' => '2026-10-05',
            'total_price' => 300000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.revenue.excel', [
            'month' => '2026-10',
        ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('bao-cao-doanh-thu-2026-10.csv', $response->headers->get('Content-Disposition'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('BÁO CÁO DOANH THU - SÂN NHÓM H', $content);
        $this->assertStringContainsString('TỔNG QUAN CHỈ SỐ DOANH THU', $content);
        $this->assertStringContainsString('DOANH THU THEO LOẠI SÂN', $content);
    }

    public function test_revenue_report_exports_pdf_view(): void
    {
        Booking::factory()->create([
            'court_id' => $this->footballCourt->id,
            'booking_date' => '2026-10-05',
            'total_price' => 300000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.revenue.pdf', [
            'month' => '2026-10',
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.pdf');
        $response->assertSee('BÁO CÁO DOANH THU');
        $response->assertSee('HỆ THỐNG SÂN THỂ THAO NHÓM H');
        $response->assertSee('Tổng doanh thu');
    }

    public function test_revenue_report_fails_validation_for_invalid_month(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.revenue', [
            'month' => 'invalid-format',
        ]));

        $response->assertSessionHasErrors(['month']);
    }
}
