<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Mảng có 6 phần tử: [Tên, Email, Mật khẩu, SĐT, Role, Ngày sinh]
        $users = [
            ['Quản trị viên',  'admin@gmail.com',   'admin123', '0900000001', 'admin',    '1990-01-01'],
            ['Nhân viên A',    'staff@gmail.com',   'staff123', '0900000002', 'staff',    '1998-05-10'],
            ['Test user',      'test@gmail.com',  '12345678', '0900000442', 'customer',    '1998-05-10'],
            ['Phuc',      'phuc@gmail.com',  '12345678', '0900023502', 'customer',    '2005-02-10'],
            ['Hai',      'hai@gmail.com',  '12345678', '0900305322', 'customer',    '2006-05-10'],
            ['Huy',      'huy@gmail.com',  '12345678', '0900632302', 'customer',    '2004-05-15'],
            ['Nguyễn Văn An',  'an@example.com',    '12345678', '0900000003', 'customer', '2003-03-15'],
            ['Trần Thị Bình',  'binh@example.com',  '12345678', '0900000004', 'customer', '2002-07-20'],
            ['Lê Minh Cường',  'cuong@example.com', '12345678', '0900000005', 'customer', '2001-11-02'],
            ['Phạm Văn Duy',   'duy@example.com',   '12345678', '0900000006', 'customer', '1999-09-09'],
            ['Hoàng Thị Mai',  'mai@example.com',   '12345678', '0900000007', 'customer', '1995-12-25'],
            ['Đỗ Thị Lan',     'lan@example.com',   '12345678', '0900000008', 'customer', '2000-01-10'],
            ['Vũ Văn Hải',     'hai@example.com',   '12345678', '0900000009', 'customer', '1998-05-14'],
            ['Bùi Thị Hạnh',   'hanh@example.com',  '12345678', '0900000010', 'customer', '1997-08-22'],
            ['Đặng Văn Khoa',  'khoa@example.com',  '12345678', '0900000011', 'customer', '2004-12-05'],
            ['Ngô Thị Hương',  'huong@example.com', '12345678', '0900000012', 'customer', '1996-03-30'],
            ['Dương Văn Minh', 'minh@example.com',  '12345678', '0900000013', 'customer', '1994-06-18'],
            ['Lý Thị Ngọc',    'ngoc@example.com',  '12345678', '0900000014', 'customer', '2001-09-12'],
            ['Trịnh Văn Phong', 'phong@example.com', '12345678', '0900000015', 'customer', '1993-11-25'],
            ['Đinh Thị Quỳnh', 'quynh@example.com', '12345678', '0900000016', 'customer', '2002-02-28'],
            ['Phan Văn Tài',   'tai@example.com',   '12345678', '0900000017', 'customer', '1999-07-07'],
            ['Lâm Thị Thảo',   'thao@example.com',  '12345678', '0900000018', 'customer', '1995-10-15'],
            ['Mai Văn Tiến',   'tien@example.com',  '12345678', '0900000019', 'customer', '2003-04-20'],
            ['Tô Thị Uyên',    'uyen@example.com',  '12345678', '0900000020', 'customer', '1998-12-12'],
            ['Hồ Văn Việt',    'viet@example.com',  '12345678', '0900000021', 'customer', '1997-01-08'],
            ['Đào Thị Xuân',   'xuan@example.com',  '12345678', '0900000022', 'customer', '2000-05-19'],
            ['Đoàn Văn Bách',  'bach@example.com',  '12345678', '0900000023', 'customer', '1996-08-04'],
            ['Trương Thị Ánh', 'anh@example.com',   '12345678', '0900000024', 'customer', '2001-03-27'],
            ['Nguyễn Văn Sơn', 'son@example.com',   '12345678', '0900000025', 'customer', '1994-09-03'],
            ['Trần Thị Tuyết', 'tuyet@example.com', '12345678', '0900000026', 'customer', '1999-11-11'],
            ['Lê Văn Đạt',     'dat@example.com',   '12345678', '0900000027', 'customer', '2002-06-22']
        ];

        $faker = \Faker\Factory::create('vi_VN');
        for ($i = 1; $i <= 20; $i++) {
            $users[] = [
                $faker->name,
                $faker->unique()->safeEmail,
                '12345678',
                $faker->numerify('09########'),
                'customer',
                $faker->date('Y-m-d', '2005-01-01')
            ];
        }
        // LƯU Ý DÒNG NÀY: Phải có đủ 6 biến (thêm $plainPassword vào vị trí thứ 3)
        foreach ($users as [$name, $email, $plainPassword, $phone, $role, $birthday]) {
            DB::table('users')->updateOrInsert(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make($plainPassword), // Mã hóa biến $plainPassword vừa lấy ở trên
                    'phone' => $phone,
                    'role' => $role,
                    'birthday' => $birthday,
                    'status' => 1,
                    'email_verified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
