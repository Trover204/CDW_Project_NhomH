<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('address', 255);
            $table->string('phone', 20)->nullable();
            $table->time('open_time');
            $table->time('close_time');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['name', 'address']);
        });

        DB::statement('ALTER TABLE facilities ADD CONSTRAINT chk_facility_time CHECK (open_time < close_time)');
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
