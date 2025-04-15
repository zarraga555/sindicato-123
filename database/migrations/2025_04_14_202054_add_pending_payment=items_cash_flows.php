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
        Schema::table('items_cash_flows', function (Blueprint $table) {
            $table->boolean('pending_payment')->nullable(); // pendiente de pago
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_cash_flows', function (Blueprint $table) {
            $table->dropColumn('pending_payment');
        });
    }
};
