<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Thay thế migration users mặc định của Laravel (xóa file cũ 0001_01_01_000000_create_users_table.php)
return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 191);
            $table->string('phone', 20)->nullable()->unique();
            $table->string('avatar', 191)->nullable();
            $table->date('birthday')->nullable();              // trang hồ sơ (>= 16 tuổi, kiểm tra ở FormRequest)
            $table->string('google_id', 191)->nullable()->unique(); // "Tiếp tục với Google"
            $table->enum('role', ['admin', 'staff', 'customer'])->default('customer');
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->index(['role', 'status']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
