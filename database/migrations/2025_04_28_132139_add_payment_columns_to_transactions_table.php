<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
            $table->decimal('amount_paid', 10, 2)->nullable(); // Jumlah yang dibayar
            $table->decimal('change_due', 10, 2)->nullable(); // Kembalian yang harus diberikan
            $table->string('payment_status')->default('unpaid'); // Status pembayaran, default 'unpaid'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['amount_paid', 'change_due', 'payment_status']);
        });
    }
};
