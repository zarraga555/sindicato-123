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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
//            $table->foreignId('driver_id')->nullable()->constrained('drivers');
//            $table->foreignId('partner_id')->nullable()->constrained('partners');
            $table->string('driver_partner')->nullable();
            $table->dateTime('dateLoan')->nullable();
            $table->string('numberInstalments')->nullable();
            $table->string('debtStatus')->nullable();
            $table->decimal('amountLoan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
