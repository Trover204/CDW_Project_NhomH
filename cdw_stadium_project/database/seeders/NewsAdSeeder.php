<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsAdSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('users')->where('email', 'admin@sannhomh.vn')->value('id');

        $news = [
            ['Ưu đãi 20% cho khách đặt sân buổi sáng tháng 9', 'uu-dai-20-dat-san-buoi-sang-thang-9', 'Khuyến mãi',
             'Giảm 20% cho mọi đơn đặt sân trước 11h sáng trong tháng 9.'],
            ['Mẹo chọn vợt cầu lông cho người mới chơi', 'meo-chon-vot-cau-long-cho-nguoi-moi', 'Thể thao',
             'Những tiêu chí cơ bản giúp bạn chọn được cây vợt phù hợp.'],
            ['Pickleball là gì? Luật chơi cơ bản', 'pickleball-la-gi-luat-choi-co-ban', 'Thể thao',
             'Giới thiệu môn pickleball đang phổ biến tại TP.HCM.'],
            ['Ra mắt gói thành viên VIP giảm đến 18%', 'ra-mat-goi-thanh-vien-vip', 'Khuyến mãi',
             'Đăng ký gói thành viên để được giảm giá mỗi lần đặt sân.'],
        ];

        foreach ($news as $i => [$title, $slug, $category, $summary]) {
            DB::table('news')->updateOrInsert(
                ['slug' => $slug],
                [
                    'title' => $title, 'content' => $summary . ' (Nội dung chi tiết bài viết mẫu.)',
                    'summary' => $summary, 'category' => $category, 'user_id' => $adminId,
                    'views' => 100 - $i * 20, 'status' => 'published',
                    'created_at' => $now, 'updated_at' => $now,
                ]
            );
        }

        $ads = [
            ['Banner khuyến mãi tháng 9', '/promo', 'Giảm 20% khung giờ sáng'],
            ['Banner gói thành viên',     '/plans', 'Đăng ký VIP giảm đến 18%'],
        ];
        DB::table('ads')->delete();
        foreach ($ads as [$name, $link, $desc]) {
            DB::table('ads')->insert([
                'name' => $name, 'link_url' => $link, 'description' => $desc,
                'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
}
