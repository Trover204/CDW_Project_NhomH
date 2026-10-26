<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        // Let's add 20 specific standalone reviews for variety.

        $reviews = [
            ['rating' => 5, 'content' => 'Sân rất đẹp, chất lượng mặt sân tuyệt vời, đá rất thích.'],
            ['rating' => 4, 'content' => 'Giá cả hợp lý so với chất lượng, nhân viên hỗ trợ nhiệt tình.'],
            ['rating' => 5, 'content' => 'Hệ thống đèn chiếu sáng tốt, đá buổi tối rất rõ.'],
            ['rating' => 4, 'content' => 'Cơ sở vật chất hiện đại, bãi đỗ xe rộng rãi thoải mái.'],
            ['rating' => 5, 'content' => 'Không gian thoáng mát, vệ sinh sạch sẽ.'],
            ['rating' => 4, 'content' => 'Nước uống và các dịch vụ đi kèm đầy đủ, rất tiện lợi.'],
            ['rating' => 5, 'content' => 'Chủ sân thân thiện, mình sẽ quay lại thường xuyên.'],
            ['rating' => 5, 'content' => 'Mặt cỏ còn mới, bám giày tốt, không bị trơn trượt.'],
            ['rating' => 5, 'content' => 'Tuyệt vời! Rất hài lòng về trải nghiệm tại đây.'],
            ['rating' => 4, 'content' => 'Sân bóng đạt chuẩn, kích thước rộng rãi, đá 5 hay 7 người đều ổn.'],
            ['rating' => 4, 'content' => 'Wifi mạnh, khu vực chờ cho người thân cũng rất thoải mái.'],
            ['rating' => 5, 'content' => 'Đặt sân dễ dàng, giá tốt, rất đáng tiền.'],
            ['rating' => 4, 'content' => 'Nhân viên bãi xe dễ thương, dắt xe dùm luôn.'],
            ['rating' => 3, 'content' => 'Sân hơi đông đúc vào cuối tuần, nhưng mặt sân vẫn rất ổn.'],
            ['rating' => 5, 'content' => 'Căng tin có nhiều đồ uống lạnh, rất sảng khoái sau khi đá.'],
            ['rating' => 4, 'content' => 'Dịch vụ đặt sân online rất tiện lợi, không phải gọi điện rườm rà.'],
            ['rating' => 5, 'content' => 'Mình đá ở đây nhiều lần rồi, chưa bao giờ thất vọng.'],
            ['rating' => 3, 'content' => 'Sân cũng tạm ổn, hơi xa nhà mình chút xíu.'],
            ['rating' => 5, 'content' => 'Đèn sáng vô địch, không lo đá tối bị chói mắt.'],
            ['rating' => 4, 'content' => 'Bạn bè mình cũng rất thích sân này, thường xuyên cáp kèo đá.'],
        ];

        foreach ($reviews as $review) {
            Review::create([
                'user_id' => $users[array_rand($users)],
                'court_id' => $courts[array_rand($courts)],
                'booking_id' => null,
                'rating' => $review['rating'],
                'content' => $review['content'],
                'status' => 1,
            ]);
        }
    }
}
