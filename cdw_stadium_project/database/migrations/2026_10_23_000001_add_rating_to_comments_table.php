<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('comments', 'rating')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->unsignedTinyInteger('rating')->default(5)->after('content');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('comments', 'rating')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('rating');
            });
        }
    }
};
