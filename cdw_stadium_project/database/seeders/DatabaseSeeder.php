<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\News;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PriceRule;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use App\Models\SportType;
use App\Models\Subscription;
use App\Models\User;
use Database\Seeders\Concerns\SyncsSearchIndex;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use SyncsSearchIndex;

    /**
     * Danh sách toàn bộ các Model liên quan đến Seeder sử dụng Laravel Scout
     * cần đồng bộ lên Elasticsearch sau khi seed.
     *
     * @var array<int, class-string>
     */
    private array $searchableModels = [
        User::class,
        SportType::class,
        Facility::class,
        Court::class,
        PriceRule::class,
        Plan::class,
        Subscription::class,
        News::class,
        Ad::class,
        Booking::class,
        Review::class,
        Product::class,
        Sale::class,
        Payment::class,
    ];

    public function run(): void
    {
        // 1. Tạm tắt tự động đồng bộ từng dòng lúc đang chạy Seeder để tối ưu tốc độ
        foreach ($this->searchableModels as $model) {
            if ($this->isModelSearchable($model)) {
                $model::disableSearchSyncing();
            }
        }

        // 2. Chạy toàn bộ Seeder tạo dữ liệu vào MySQL
        $this->call([
            UserSeeder::class,
            SportTypeSeeder::class,
            FacilitySeeder::class,
            CourtSeeder::class,
            PriceRuleSeeder::class,
            PlanSeeder::class,
            SubscriptionSeeder::class,
            NewsAdSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
            ProductSeeder::class,
            SaleSeeder::class,
            PaymentSeeder::class,
        ]);

        // 3. Bật lại đồng bộ và đẩy hàng loạt (Bulk Import) dữ liệu mới lên Elasticsearch
        foreach ($this->searchableModels as $model) {
            if ($this->isModelSearchable($model)) {
                $model::enableSearchSyncing();
                $this->syncSearchIndex($model);
            }
        }
    }
}
