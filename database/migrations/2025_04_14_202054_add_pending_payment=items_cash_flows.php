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
            $table->boolean('pending_payment')->nullable()->default(0.00); // pendiente de pago
            $table->double('amount')->nullable()->default(false); // monto 
        });
    }

    /**
     * Reverse the migrations.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
     */
    public function down(): void
    {
        Schema::table('items_cash_flows', function (Blueprint $table) {
            $table->dropColumn('pending_payment');
            $table->dropColumn('amount');
        });
    }
};
