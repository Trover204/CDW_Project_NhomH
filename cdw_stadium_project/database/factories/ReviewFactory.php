<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Court;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('vi_VN');

        $sampleContents = [
            'Sân rất đẹp, chất lượng mặt sân tuyệt vời, đá rất thích.',
            'Giá cả hợp lý so với chất lượng, nhân viên hỗ trợ nhiệt tình.',
            'Hệ thống đèn chiếu sáng tốt, đá buổi tối rất rõ.',
            'Cơ sở vật chất hiện đại, bãi đỗ xe rộng rãi thoải mái.',
            'Không gian thoáng mát, vệ sinh sạch sẽ.',
            'Nước uống và các dịch vụ đi kèm đầy đủ, rất tiện lợi.',
            'Chủ sân thân thiện, mình sẽ quay lại thường xuyên.',
            'Mặt cỏ còn mới, bám giày tốt, không bị trơn trượt.',
            'Tuyệt vời! Rất hài lòng về trải nghiệm tại đây.',
            'Sân bóng đạt chuẩn, kích thước rộng rãi, đá 5 hay 7 người đều ổn.',
            'Wifi mạnh, khu vực chờ cho người thân cũng rất thoải mái.',
            'Đặt sân dễ dàng, giá tốt, rất đáng tiền.',
        ];

        return [
            'user_id' => User::factory(),
            'court_id' => Court::factory(),
            'rating' => $faker->numberBetween(1, 5),
            'content' => $faker->randomElement($sampleContents),
            'status' => 1,
        ];
    }
}
