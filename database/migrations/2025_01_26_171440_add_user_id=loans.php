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
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users'); // id del uduario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Primero elimina la clave foránea
            $table->dropForeign(['user_id']); // 'loans_user_id_foreign'

            // Luego elimina la columna
            $table->dropColumn('user_id');
        });
    }
};
