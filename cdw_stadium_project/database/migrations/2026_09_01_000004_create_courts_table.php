<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->restrictOnDelete();
            $table->foreignId('sport_type_id')->constrained()->restrictOnDelete();
            $table->string('name', 191);
            $table->unsignedInteger('capacity')->default(1);
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'maintenance', 'inactive'])->default('available');
            $table->timestamps();

            $table->unique(['facility_id', 'name']);
            $table->index(['sport_type_id', 'status']);
        });

        DB::statement('ALTER TABLE courts ADD CONSTRAINT chk_court_capacity CHECK (capacity > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
