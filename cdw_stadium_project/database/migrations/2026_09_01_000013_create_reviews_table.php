<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            // unique: mỗi đơn chỉ đánh giá một lần ("Bạn đã đánh giá lượt đặt này rồi")
            $table->foreignId('booking_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('content')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['court_id', 'status']);
        });

        DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_review_rating CHECK (rating BETWEEN 1 AND 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
