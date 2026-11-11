<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sampleComments = [
            'Bài viết rất hữu ích và chi tiết, cảm ơn Ban Biên Tập Sân Nhóm H đã chia sẻ!',
            'Thông tin cập nhật nhanh chóng, mình đã áp dụng mẹo này khi ra sân tập luyện và thấy hiệu quả rõ rệt.',
            'Chương trình ưu đãi quá tuyệt vời, đội bóng của mình chắc chắn sẽ đặt lịch cố định hàng tuần.',
            'Nội dung phân tích rất chuyên sâu, hình ảnh đẹp và trình bày cực kỳ dễ theo dõi.',
            'Rất thích chuyên mục tin tức thể thao của hệ thống, mong có thêm nhiều bài hướng dẫn kỹ thuật nữa.',
            'Đặt sân qua hệ thống vừa nhanh vừa tiện, đọc thêm các bài cẩm nang này giúp anh em cải thiện thể lực tốt hơn.',
            'Bài viết truyền cảm hứng thực sự! Cuối tuần này phải rủ hội bạn ra sân chiến ngay một trận mới được.',
            'Giải thích luật chơi và kỹ thuật rất dễ hiểu cho người mới bắt đầu tìm hiểu bộ môn này.',
            'Cảm ơn thông tin từ Sân Nhóm H, dịch vụ tại các cơ sở luôn làm mình và đồng đội hài lòng.',
            'Đã lưu lại bài viết để chia sẻ cho cả câu lạc bộ cùng đọc trước giải giao hữu sắp tới.',
        ];

        return [
            'user_id' => User::factory(),
            'news_id' => News::factory(),
            'rating' => $this->faker->numberBetween(4, 5),
            'content' => $this->faker->randomElement($sampleComments),
            'status' => true,
            'version' => 0,
        ];
    }
}
