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
            $table->foreignId('cash_drawer_id')->nullable()->constrained('cash_drawers'); // id del uduario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            // Primero elimina la clave foránea
            $table->dropForeign(['cash_drawer_id']); // 'loans_user_id_foreign'

            // Luego elimina la columna
            $table->dropColumn('cash_drawer_id');
        });
    }
};
