<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->boolean('status')->default(true);
            // Giá gói tại thời điểm đăng ký. Thông tin thanh toán (phương thức, mã giao dịch, trạng thái)
            // được lưu ở bảng payments (payable_type = 'subscription').
            $table->decimal('price', 12, 0)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status', 'expires_at']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_sub_time CHECK (expires_at > starts_at)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
