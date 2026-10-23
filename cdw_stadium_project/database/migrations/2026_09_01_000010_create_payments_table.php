<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Bảng thanh toán tập trung: mọi đơn phát sinh tiền (đặt sân, bán hàng, gói thành viên)
         * đều xuất dữ liệu thanh toán qua bảng này (quan hệ đa hình 1-1).
         *   payable_type: booking | sale | subscription (morph map trong AppServiceProvider)
         *   payable_id  : id của đơn nguồn tương ứng
         */
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payable_type', 50);
            $table->unsignedBigInteger('payable_id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // khách lẻ mua hàng có thể không có tài khoản
            $table->decimal('amount', 12, 0);
            $table->string('payment_method', 50);           // cash, cod, bank_transfer, vnpay, momo, visa, paypal
            $table->string('transaction_code', 191)->nullable()->unique();
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();       // mốc ghi nhận doanh thu
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['payable_type', 'payable_id'], 'uq_payment_payable'); // mỗi đơn có đúng 1 thanh toán
            $table->index(['status', 'paid_at']);
            $table->index(['payable_type', 'status', 'paid_at'], 'idx_payments_type_status_paid');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payment_amount CHECK (amount >= 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
