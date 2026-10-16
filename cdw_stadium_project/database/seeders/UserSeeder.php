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
    ['Phạm Hoàng Long',    'long.pham@example.com',    '12345678', '0900000011', 'customer', '2000-01-12'],
    ['Võ Thị Ngọc Mai',    'mai.vo@example.com',       '12345678', '0900000012', 'customer', '2002-04-25'],
    ['Đặng Quốc Huy',      'huy.dang@example.com',     '12345678', '0900000013', 'customer', '2001-08-17'],
    ['Bùi Thanh Tâm',      'tam.bui@example.com',      '12345678', '0900000014', 'customer', '2003-02-09'],
    ['Đỗ Minh Khang',      'khang.do@example.com',     '12345678', '0900000015', 'customer', '1999-06-30'],
    ['Hồ Thị Thu Hà',      'ha.ho@example.com',        '12345678', '0900000016', 'customer', '2002-10-14'],
    ['Ngô Đức Anh',        'anh.ngo@example.com',      '12345678', '0900000017', 'customer', '2000-12-05'],
    ['Dương Gia Bảo',      'bao.duong@example.com',    '12345678', '0900000018', 'customer', '2003-07-22'],
    ['Lý Khánh Linh',      'linh.ly@example.com',      '12345678', '0900000019', 'customer', '2001-03-18'],
    ['Mai Tuấn Kiệt',      'kiet.mai@example.com',     '12345678', '0900000020', 'customer', '1998-09-11'],

    ['Trương Mỹ Duyên',    'duyen.truong@example.com', '12345678', '0900000021', 'customer', '2002-05-16'],
    ['Phan Nhật Nam',      'nam.phan@example.com',     '12345678', '0900000022', 'customer', '2000-11-27'],
    ['Vũ Hoàng Phúc',      'phuc.vu@example.com',      '12345678', '0900000023', 'customer', '2001-01-08'],
    ['Cao Thị Kim Oanh',   'oanh.cao@example.com',     '12345678', '0900000024', 'customer', '1999-04-19'],
    ['Huỳnh Minh Trí',     'tri.huynh@example.com',    '12345678', '0900000025', 'customer', '2003-09-03'],
    ['Tạ Ngọc Trâm',       'tram.ta@example.com',      '12345678', '0900000026', 'customer', '2002-12-21'],
    ['Đinh Thành Đạt',     'dat.dinh@example.com',     '12345678', '0900000027', 'customer', '2000-07-13'],
    ['Lâm Bảo Ngọc',       'ngoc.lam@example.com',     '12345678', '0900000028', 'customer', '2001-05-29'],
    ['Phùng Anh Tuấn',     'tuan.phung@example.com',   '12345678', '0900000029', 'customer', '1998-02-14'],
    ['Tô Thị Hồng Nhung',  'nhung.to@example.com',     '12345678', '0900000030', 'customer', '2003-06-07'],

    ['Nguyễn Thành Công',  'cong.nguyen@example.com',  '12345678', '0900000031', 'customer', '2000-08-24'],
    ['Trần Quốc Bảo',      'bao.tran@example.com',     '12345678', '0900000032', 'customer', '2002-01-31'],
    ['Lê Thị Thanh Thảo',  'thao.le@example.com',      '12345678', '0900000033', 'customer', '2001-10-06'],
    ['Phạm Gia Hân',       'han.pham@example.com',     '12345678', '0900000034', 'customer', '2003-04-12'],
    ['Võ Minh Quân',       'quan.vo@example.com',      '12345678', '0900000035', 'customer', '1999-12-18'],
    ['Đặng Thùy Trang',    'trang.dang@example.com',   '12345678', '0900000036', 'customer', '2002-06-23'],
    ['Bùi Quốc Thịnh',     'thinh.bui@example.com',    '12345678', '0900000037', 'customer', '2000-03-09'],
    ['Đỗ Ngọc Ánh',        'anh.do@example.com',       '12345678', '0900000038', 'customer', '2001-07-26'],
    ['Hồ Minh Nhật',       'nhat.ho@example.com',      '12345678', '0900000039', 'customer', '2003-11-15'],
    ['Ngô Thị Diễm My',    'my.ngo@example.com',       '12345678', '0900000040', 'customer', '2002-02-28'],

    ['Dương Quốc Việt',    'viet.duong@example.com',   '12345678', '0900000041', 'customer', '1998-10-09'],
    ['Lý Hoài Phương',     'phuong.ly@example.com',    '12345678', '0900000042', 'customer', '2001-12-17'],
    ['Mai Đức Thành',      'thanh.mai@example.com',    '12345678', '0900000043', 'customer', '2000-05-04'],
    ['Trương Khả Hân',     'khan.truong@example.com',  '12345678', '0900000044', 'customer', '2003-08-20'],
    ['Phan Văn Khải',      'khai.phan@example.com',    '12345678', '0900000045', 'customer', '1999-01-25'],
    ['Vũ Thị Bích Ngọc',   'ngoc.vu@example.com',      '12345678', '0900000046', 'customer', '2002-09-13'],
    ['Cao Minh Hoàng',     'hoang.cao@example.com',    '12345678', '0900000047', 'customer', '2001-06-02'],
    ['Huỳnh Thanh Phong',  'phong.huynh@example.com',  '12345678', '0900000048', 'customer', '2000-02-16'],
    ['Tạ Minh Châu',       'chau.ta@example.com',      '12345678', '0900000049', 'customer', '2003-10-28'],
    ['Đinh Quốc Khánh',    'khanh.dinh@example.com',   '12345678', '0900000050', 'customer', '1999-07-07'],

    ['Lâm Thị Quỳnh Anh',  'anh.lam@example.com',      '12345678', '0900000051', 'customer', '2002-03-11'],
    ['Phùng Minh Đức',     'duc.phung@example.com',    '12345678', '0900000052', 'customer', '2001-09-24'],
    ['Tô Gia Linh',        'linh.to@example.com',      '12345678', '0900000053', 'customer', '2000-04-06'],
    ['Nguyễn Hải Đăng',    'dang.nguyen@example.com',  '12345678', '0900000054', 'customer', '2003-12-19'],
    ['Trần Thị Yến Nhi',   'nhi.tran@example.com',     '12345678', '0900000055', 'customer', '2002-08-01'],
    ['Lê Quang Vinh',      'vinh.le@example.com',      '12345678', '0900000056', 'customer', '1998-11-22'],
    ['Phạm Thị Lan Anh',   'lananh.pham@example.com',  '12345678', '0900000057', 'customer', '2001-02-07'],
    ['Võ Thành Luân',      'luan.vo@example.com',      '12345678', '0900000058', 'customer', '2000-09-16'],
    ['Đặng Minh Thư',      'thu.dang@example.com',     '12345678', '0900000059', 'customer', '2003-05-27'],
    ['Bùi Anh Dũng',       'dung.bui@example.com',     '12345678', '0900000060', 'customer', '1999-03-14'],
];

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
