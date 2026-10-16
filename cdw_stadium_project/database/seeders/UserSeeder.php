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
            ['Nguyễn Văn An',  'an@example.com',    '12345678', '0900000003', 'customer', '2003-03-15'],
            ['Trần Thị Bình',  'binh@example.com',  '12345678', '0900000004', 'customer', '2002-07-20'],
            ['Lê Minh Cường',  'cuong@example.com', '12345678', '0900000005', 'customer', '2001-11-02'],
            ['Phạm Văn Duy',   'duy@example.com',   '12345678', '0900000006', 'customer', '1999-09-09'],
            ['Hoàng Thị Mai',  'mai@example.com',   '12345678', '0900000007', 'customer', '1995-12-25'],
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
