<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'customer', 'ctv', 'pending_ctv') NOT NULL DEFAULT 'customer'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE users SET role = 'customer' WHERE role IN ('ctv', 'pending_ctv')");
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer'");
        }
    }
};
