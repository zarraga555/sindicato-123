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
            $table->string('interest_rate')->nullable(); // % de interes del prestamo
            $table->decimal('total_debt', 21, 2)->nullable(); // Total de la deuda a cobrar
            $table->string('user_type')->nullable(); // Tipo de usuario (driver; partner) TEMPORAL
            $table->string('payment_frequency')->nullable(); // Frecuencia del pago (semanal; mensual)
            $table->foreignId('cash_flows_id')->nullable()->constrained('cash_flows'); // id de la transacion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('interest_rate'); // Eliminar la columna si se revierte la migración
            $table->dropColumn('total_debt');
            $table->dropColumn('user_type');
            $table->dropColumn('payment_frequency');
            $table->dropColumn('cash_flows_id');
        });
    }
};
