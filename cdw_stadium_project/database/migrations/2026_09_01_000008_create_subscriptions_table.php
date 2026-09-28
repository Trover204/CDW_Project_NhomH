<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->boolean('status')->default(true);
            $table->string('payment_method', 191)->nullable();
            $table->decimal('amount_paid', 12, 0)->default(0);
            $table->string('transaction_code', 191)->nullable()->unique();
            $table->timestamps();

            $table->index(['user_id', 'status', 'expires_at']);
        });

        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_sub_time CHECK (expires_at > starts_at)');
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
