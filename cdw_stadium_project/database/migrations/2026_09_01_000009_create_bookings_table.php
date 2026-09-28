<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('court_id')->constrained()->restrictOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();

            // Thông tin trên form đặt sân (mục 4.8)
            $table->string('customer_name', 50);
            $table->string('customer_phone', 20);
            $table->string('customer_email', 191);

            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->decimal('subtotal', 12, 0)->default(0);         // đơn giá trước giảm
            $table->decimal('discount_amount', 12, 0)->default(0);  // giảm giá VIP
            $table->decimal('total_price', 12, 0)->default(0);      // = subtotal - discount_amount
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled', 'rejected'])
                  ->default('pending');

            // Cột sinh tự động: NULL khi đơn đã hủy/từ chối => không chặn đặt lại khung giờ đó
            $table->unsignedTinyInteger('slot_lock')->nullable()
                  ->storedAs("IF(status IN ('cancelled','rejected'), NULL, 1)");

            $table->timestamps();

            $table->unique(['court_id', 'booking_date', 'start_time', 'slot_lock'], 'uq_booking_slot');
            $table->index(['court_id', 'booking_date']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'booking_date']);
        });

        DB::statement('ALTER TABLE bookings ADD CONSTRAINT chk_booking_time CHECK (start_time < end_time)');
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT chk_booking_total CHECK (total_price >= 0 AND discount_amount >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
