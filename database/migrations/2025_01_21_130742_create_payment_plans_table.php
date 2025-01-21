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
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('instalmentNumber')->nullable();
            $table->dateTime('datePayment')->nullable();
            $table->string('paymentStatus')->nullable();
            $table->decimal('amount', 20, 2)->nullable();
            $table->foreignId('loan_id')->nullable()->constrained('loans');
            $table->string('attachment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_plans');
    }
};
