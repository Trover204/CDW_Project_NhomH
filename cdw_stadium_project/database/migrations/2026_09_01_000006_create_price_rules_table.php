<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            // NULL = giá chung theo loại môn; có giá trị = giá riêng của sân (ưu tiên hơn)
            $table->foreignId('court_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('sport_type_id')->constrained()->restrictOnDelete();
            $table->enum('day_type', ['weekday', 'weekend', 'holiday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('price', 12, 0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Lưu ý: MySQL cho phép nhiều dòng NULL trong UNIQUE, nên với court_id = NULL
            // cần kiểm tra trùng khung giá thêm ở FormRequest.
            $table->unique(['court_id', 'sport_type_id', 'day_type', 'start_time', 'end_time'], 'uq_price_rule');
            $table->index(['court_id', 'day_type', 'start_time']);
        });

        DB::statement('ALTER TABLE price_rules ADD CONSTRAINT chk_price_time CHECK (start_time < end_time)');
        DB::statement('ALTER TABLE price_rules ADD CONSTRAINT chk_price_value CHECK (price >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
