<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->string('description')->nullable();
            $table->foreignId('cash_flow_id')->nullable()->constrained('cash_flows'); // id del uduario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->string('description')->nullable();
            $table->dropForeign(['cash_flow_id']);
            $table->dropColumn('cash_flow_id');
        });
    }
};
