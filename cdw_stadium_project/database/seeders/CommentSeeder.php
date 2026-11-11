<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds (25 sample reviews/comments for News articles).
     */
    public function run(): void
    {
        $users = DB::table('users')->where('role', 'customer')->pluck('id')->toArray();
        if (empty($users)) {
            $users = DB::table('users')->pluck('id')->toArray();
        }

        $newsBySlug = DB::table('news')->pluck('id', 'slug')->toArray();
        $allNewsIds = array_values($newsBySlug);

        if (empty($users) || empty($allNewsIds)) {
            return;
        }

        $sampleReviewsForNews = [
            [
                'slug' => 'uu-dai-20-dat-san-buoi-sang-thang-9',
                'rating' => 5,
                'content' => 'Chương trình ưu đãi 20% buổi sáng rất thiết thực! Đội bóng công ty mình vừa đặt sân lúc 7h sáng, mặt cỏ êm và tiết kiệm được kha khá chi phí.',
                'status' => 1,
                'days_ago' => 12,
            ],
            [
                'slug' => 'uu-dai-20-dat-san-buoi-sang-thang-9',
                'rating' => 5,
                'content' => 'Mình hay đánh cầu lông ca 6h-8h sáng trước khi đi làm, có thêm chương trình giảm 20% này thực sự quá tuyệt vời. Cảm ơn Sân Nhóm H!',
                'status' => 1,
                'days_ago' => 10,
            ],
            [
                'slug' => 'meo-chon-vot-cau-long-cho-nguoi-moi',
                'rating' => 5,
                'content' => 'Bài viết chia sẻ rất chi tiết và dễ hiểu. Nhờ đọc bài này mà mình chọn được cây vợt 4U cán G5 vừa tay, đánh đỡ mỏi cổ tay hẳn.',
                'status' => 1,
                'days_ago' => 11,
            ],
            [
                'slug' => 'meo-chon-vot-cau-long-cho-nguoi-moi',
                'rating' => 4,
                'content' => 'Mình mới tập chơi cầu lông được 2 tuần, trước cứ tưởng vợt càng nặng đập càng mạnh. Bài viết thực sự rất hữu ích cho người mới!',
                'status' => 1,
                'days_ago' => 8,
            ],
            [
                'slug' => 'pickleball-la-gi-luat-choi-co-ban',
                'rating' => 5,
                'content' => 'Pickleball công nhận dễ tiếp cận và cuốn thật sự! Cuối tuần vừa rồi cả gia đình mình ra sân trải nghiệm thử mà ai cũng mê.',
                'status' => 1,
                'days_ago' => 9,
            ],
            [
                'slug' => 'pickleball-la-gi-luat-choi-co-ban',
                'rating' => 5,
                'content' => 'Giải thích luật two-bounce và vùng kitchen rất rõ ràng. Mong hệ thống mở thêm nhiều cụm sân Pickleball có mái che hơn nữa.',
                'status' => 1,
                'days_ago' => 6,
            ],
            [
                'slug' => 'ra-mat-goi-thanh-vien-vip',
                'rating' => 5,
                'content' => 'Đã đăng ký gói thành viên VIP 3 tháng, tính ra tuần nào cũng đặt sân đá cố định thì tiết kiệm hơn rất nhiều, lại được ưu tiên giữ lịch giờ vàng.',
                'status' => 1,
                'days_ago' => 9,
            ],
            [
                'slug' => 'bong-ro-mon-the-thao-moi',
                'rating' => 5,
                'content' => 'Mặt sân bóng rổ mới có lớp đệm giảm chấn cực êm, bật nhảy lên rổ không bị đau gối, bảng rổ kính cường lực chuẩn thi đấu luôn!',
                'status' => 1,
                'days_ago' => 8,
            ],
            [
                'slug' => 'huong-dan-dat-san-va-thanh-toan',
                'rating' => 4,
                'content' => 'Thanh toán qua QR VNPay siêu nhanh, quét mã cái là nhận được xác nhận giữ sân ngay lập tức qua email, không cần phải gọi điện chờ đợi.',
                'status' => 1,
                'days_ago' => 7,
            ],
            [
                'slug' => 'lionel-messi-va-argentina-dinh-cao-vinh-quang-huyen-thoai',
                'rating' => 5,
                'content' => 'Bài viết về Messi giàu cảm xúc quá! Chức vô địch World Cup thực sự là cái kết viên mãn nhất cho sự nghiệp vĩ đại của El Pulga.',
                'status' => 1,
                'days_ago' => 7,
            ],
            [
                'slug' => 'lionel-messi-va-argentina-dinh-cao-vinh-quang-huyen-thoai',
                'rating' => 5,
                'content' => 'Xem Messi chơi bóng luôn mang lại nguồn cảm hứng đặc biệt để anh em xỏ giày ra sân cỏ nhân tạo mỗi chiều cuối tuần.',
                'status' => 1,
                'days_ago' => 5,
            ],
            [
                'slug' => 'cristiano-ronaldo-y-chi-phi-thuong-ky-luc-bat-tan',
                'rating' => 5,
                'content' => 'Tinh thần kỷ luật sắt đá và ý chí tập luyện của CR7 thực sự đáng ngưỡng mộ. Ở tuổi này mà thể lực và khả năng bật nhảy vẫn quá đỉnh!',
                'status' => 1,
                'days_ago' => 6,
            ],
            [
                'slug' => 'erling-haaland-quai-vat-ghi-ban-manchester-city',
                'rating' => 5,
                'content' => 'Haaland đúng nghĩa cỗ máy săn bàn tại Ngoại hạng Anh, khả năng chọn vị trí không chiến và dứt điểm một chạm quá đáng sợ.',
                'status' => 1,
                'days_ago' => 6,
            ],
            [
                'slug' => 'vinicius-junior-ngoi-no-ma-thuat-real-madrid',
                'rating' => 4,
                'content' => 'Những pha bứt tốc xé gió và qua người bên hành lang cánh trái của Vinicius ở Champions League xem mãn nhãn thật sự!',
                'status' => 1,
                'days_ago' => 5,
            ],
            [
                'slug' => 'bruno-fernandes-bo-nao-sang-tao-manchester-united',
                'rating' => 5,
                'content' => 'Bruno luôn cháy hết mình suốt 90 phút trên sân, khả năng chuyền bóng mở biên và tạo đột biến của anh vẫn là linh hồn của Quỷ Đỏ.',
                'status' => 1,
                'days_ago' => 5,
            ],
            [
                'slug' => 'dai-chien-champions-league-cuoc-do-suc-dinh-cao',
                'rating' => 5,
                'content' => 'Những đêm thức xem đại chiến Champions League giữa Real Madrid và Bayern Munich chưa bao giờ làm người hâm mộ túc cầu thất vọng!',
                'status' => 1,
                'days_ago' => 4,
            ],
            [
                'slug' => 'karim-benzema-marcelo-ky-nguyen-hoang-kim-bernabeu',
                'rating' => 5,
                'content' => 'Nhớ thời Benzema phối hợp ăn ý cùng Marcelo bên cánh trái của Real quá, một kỷ nguyên hoàng kim đầy ắp danh hiệu cúp C1.',
                'status' => 1,
                'days_ago' => 4,
            ],
            [
                'slug' => 'ousmane-dembele-dien-mao-tan-cong-paris-saint-germain',
                'rating' => 4,
                'content' => 'Dembélé thuận cả hai chân nên hậu vệ đối phương rất khó bắt bài, mùa này thi đấu ngày càng gắn kết và hiệu quả hơn.',
                'status' => 1,
                'days_ago' => 4,
            ],
            [
                'slug' => 'ousmane-dembele-toa-sang-doi-tuyen-phap',
                'rating' => 5,
                'content' => 'Đội hình tuyển Pháp có chiều sâu thật sự, những pha leo biên tốc độ của Dembélé luôn mở ra khoảng trống lớn cho hàng tiền đạo.',
                'status' => 1,
                'days_ago' => 3,
            ],
            [
                'slug' => 'khoanh-khac-nang-cup-vinh-quang-tinh-than-dong-doi',
                'rating' => 5,
                'content' => 'Đọc bài viết mà nhớ cảm giác đội bóng phủi của anh em mình cùng nhau nâng cúp giải giao hữu tại Sân Nhóm H tháng trước!',
                'status' => 1,
                'days_ago' => 3,
            ],
            [
                'slug' => 'khvicha-kvaratskhelia-hien-tuong-san-co-dong-au',
                'rating' => 4,
                'content' => 'Kvaratskhelia có lối đi bóng rất ngẫu hứng và lì đòn, một trong những cầu thủ chạy cánh giàu đột biến đáng xem nhất châu Âu hiện nay.',
                'status' => 1,
                'days_ago' => 3,
            ],
            [
                'slug' => 'bukayo-saka-lan-song-bong-da-anh-dau-truong-chau-luc',
                'rating' => 5,
                'content' => 'Saka ngày càng hoàn thiện và bản lĩnh, luôn biết cách tỏa sáng đúng lúc ở những trận cầu đinh cho Pháo thủ Arsenal.',
                'status' => 1,
                'days_ago' => 2,
            ],
            [
                'slug' => 'nguyen-quang-hai-dang-cap-ngoi-sao-tuyen-viet-nam',
                'rating' => 5,
                'content' => 'Những cú cứa lòng chân trái và siêu phẩm đá phạt của Quang Hải vẫn luôn mang lại cảm xúc vỡ òa cho hàng triệu người hâm mộ Việt Nam!',
                'status' => 1,
                'days_ago' => 2,
            ],
            [
                'slug' => 'nguyen-dinh-bac-toa-sang-vleague-cong-an-ha-noi',
                'rating' => 5,
                'content' => 'Đình Bắc còn trẻ mà thi đấu rất tự tin, tốc độ bứt phá và khả năng dứt điểm quyết đoán, tương lai rất sáng của hàng công tuyển Việt Nam.',
                'status' => 1,
                'days_ago' => 1,
            ],
            [
                'slug' => 'doi-tuyen-viet-nam-thang-hoa-tinh-than-chien-binh',
                'rating' => 5,
                'content' => 'Luôn tự hào và đồng hành cùng Đội tuyển Việt Nam! Chúc các chiến binh sao vàng giữ vững phong độ rực lửa ở các giải đấu quốc tế sắp tới.',
                'status' => 1,
                'days_ago' => 1,
            ],
        ];

        $hasRatingColumn = Schema::hasColumn('comments', 'rating');

        foreach ($sampleReviewsForNews as $index => $item) {
            $newsId = $newsBySlug[$item['slug']] ?? $allNewsIds[$index % count($allNewsIds)];
            $userId = $users[$index % count($users)];
            $timestamp = now()->subDays($item['days_ago'])->subHours($index % 12);

            $values = [
                'user_id' => $userId,
                'status' => $item['status'],
                'version' => 0,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            if ($hasRatingColumn) {
                $values['rating'] = $item['rating'];
            }

            DB::table('comments')->updateOrInsert(
                [
                    'news_id' => $newsId,
                    'content' => $item['content'],
                ],
                $values
            );
        }
    }
}
