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
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->string('payment_type')->nullable(); // Tipo de pago
            $table->string('payment_status')->nullable(); // Estado de pago
            $table->string('transaction_status')->nullable(); // Estado de transaccion

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_status');
            $table->dropColumn('transaction_status');
        });
    }
};
