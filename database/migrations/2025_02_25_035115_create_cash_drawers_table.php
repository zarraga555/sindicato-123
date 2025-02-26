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
        Schema::create('cash_drawers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que realiza el arqueo
            $table->dateTime('start_time'); // Fecha y hora de inicio del arqueo
            $table->dateTime('end_time')->nullable(); // Fecha y hora de cierre del arqueo
            $table->double('initial_money', 15, 2)->default(0); // Dinero en efectivo al iniciar
            $table->double('final_money', 15, 2)->default(0); // Dinero en efectivo al cerrar
            $table->double('digital_payments', 15, 2)->default(0); // Pagos digitales registrados
            $table->double('total_calculated', 15, 2)->default(0); // Total calculado según transacciones
            $table->double('total_declared', 15, 2)->default(0); // Total declarado por el usuario
            $table->double('difference', 15, 2)->default(0); // Diferencia entre lo esperado y lo real
            $table->enum('status', ['open', 'parcial','closed'])->default('open'); // Estado del arqueo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_drawers');
    }
};
