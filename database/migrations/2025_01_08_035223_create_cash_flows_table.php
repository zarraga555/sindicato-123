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
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->double('amount')->nullable();
            $table->string('detail')->nullable();
            $table->string('transaction_type_income_expense')->nullable();


            $table->foreignId('account_bank_id')->nullable()->constrained('account_letters');
//            $table->foreignId('driver_id')->nullable()->constrained('drivers');
//            $table->foreignId('partner_id')->nullable()->constrained('partners');
            $table->foreignId('items_id')->nullable()->constrained('items_cash_flows');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
