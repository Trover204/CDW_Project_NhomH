<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Các bảng cần bổ sung cột `version` để hỗ trợ Optimistic Locking toàn hệ thống.
     * (4 bảng facilities, sport_types, courts, comments đã có sẵn cột version từ migration gốc)
     *
     * @var list<string>
     */
    private const TABLES = [
        'products',
        'sales',
        'bookings',
        'subscriptions',
        'plans',
        'payments',
        'reviews',
        'users',
        'news',
        'ads',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'version')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedInteger('version')->default(0);
                });
            }
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'version')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('version');
                });
            }
        }
    }
};
