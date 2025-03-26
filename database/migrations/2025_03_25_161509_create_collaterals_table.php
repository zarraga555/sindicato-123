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
        Schema::create('collaterals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
            $table->string('driver_partner_name')->nullable();
            $table->dateTime('start_date')->nullable(); // Fecha de inicio 
            $table->string('instalments')->nullable(); // Cantidad de cuotas 
            $table->string('status')->nullable(); // Estado
            $table->decimal('amount')->nullable(); // Monto 
            $table->dateTime('registration_date')->nullable(); // Fecha de registro
            $table->string('user_type')->nullable(); // Tipo de usuario (driver; partner) TEMPORAL
            $table->string('payment_frequency')->nullable(); // Frecuencia del pago (semanal; mensual)
            $table->foreignId('cash_flows_id')->nullable()->constrained('cash_flows'); // id de la transacion
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('description')->nullable(); // Descripcion de la transaccion
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaterals');
    }
};
