<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bảng bổ sung cho trang Cài đặt hệ thống (mục 4.21)
return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('settings')->insert([
            ['key' => 'site_name',              'value' => 'Sân Nhóm H',  'created_at' => $now, 'updated_at' => $now],
            ['key' => 'free_cancel_hours',      'value' => '2',           'created_at' => $now, 'updated_at' => $now],
            ['key' => 'auto_cancel_minutes',    'value' => '15',          'created_at' => $now, 'updated_at' => $now],
            ['key' => 'require_manual_approval','value' => '0',           'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
