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
            $table->string('roadmap_series')->nullable()->after('created_at'); // Nueva columna 'descripcion'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_flows', function (Blueprint $table) {
            $table->dropColumn('roadmap_series'); // Eliminar la columna si se revierte la migración
        });
    }
};
