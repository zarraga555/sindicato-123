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
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->foreignId('collateral_id')->nullable()->constrained('collaterals'); // id de la garantia
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_plans', function (Blueprint $table) {
            // Primero elimina la clave foránea
            $table->dropForeign(['collateral_id']); // 'loans_user_id_foreign'

            // Luego elimina la columna
            $table->dropColumn('collateral_id');
        });
    }
};
