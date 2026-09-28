<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->unsignedInteger('duration_days');
            $table->decimal('price', 12, 0);
            $table->unsignedTinyInteger('discount_percent')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE plans ADD CONSTRAINT chk_plan_duration CHECK (duration_days > 0)');
        DB::statement('ALTER TABLE plans ADD CONSTRAINT chk_plan_price CHECK (price >= 0)');
        DB::statement('ALTER TABLE plans ADD CONSTRAINT chk_plan_discount CHECK (discount_percent BETWEEN 0 AND 100)');
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
