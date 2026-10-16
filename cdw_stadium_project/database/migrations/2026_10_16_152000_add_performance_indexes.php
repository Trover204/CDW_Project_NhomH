<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_news_status_created_at');
            $table->index('created_at', 'idx_news_created_at');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('created_at', 'idx_activity_logs_created_at');
        });

        Schema::table('court_user_likes', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'idx_likes_user_created_at');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index('created_at', 'idx_comments_created_at');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('created_at', 'idx_bookings_created_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('created_at', 'idx_payments_created_at');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('idx_news_status_created_at');
            $table->dropIndex('idx_news_created_at');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_activity_logs_created_at');
        });

        Schema::table('court_user_likes', function (Blueprint $table) {
            $table->dropIndex('idx_likes_user_created_at');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('idx_comments_created_at');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_created_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_created_at');
        });
    }
};
