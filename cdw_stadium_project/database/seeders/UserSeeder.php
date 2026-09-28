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
        $password = Hash::make('password'); // mật khẩu mẫu cho mọi tài khoản: password

        $users = [
            ['Quản trị viên',  'admin@sannhomh.vn',     '0900000001', 'admin',    '1990-01-01'],
            ['Nhân viên A',    'staff@sannhomh.vn',     '0900000002', 'staff',    '1998-05-10'],
            ['Nguyễn Văn An',  'an@example.com',        '0900000003', 'customer', '2003-03-15'],
            ['Trần Thị Bình',  'binh@example.com',      '0900000004', 'customer', '2002-07-20'],
            ['Lê Minh Cường',  'cuong@example.com',     '0900000005', 'customer', '2001-11-02'],
        ];

        foreach ($users as [$name, $email, $phone, $role, $birthday]) {
            DB::table('users')->updateOrInsert(
                ['email' => $email],
                [
                    'name' => $name, 'password' => $password, 'phone' => $phone,
                    'role' => $role, 'birthday' => $birthday, 'status' => 1,
                    'email_verified_at' => $now, 'created_at' => $now, 'updated_at' => $now,
                ]
            );
        }
    }
}
