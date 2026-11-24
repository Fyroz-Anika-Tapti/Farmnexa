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
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('customer_code', 50)->unique();
        $table->string('customer_name', 150);
        $table->string('phone', 30)->nullable();
        $table->string('email', 100)->nullable();
        $table->text('address')->nullable();
        $table->string('customer_type', 30)->default('retail'); // retail, wholesale, corporate
        $table->decimal('opening_balance', 14, 2)->default(0);
        $table->decimal('current_balance', 14, 2)->default(0); // due amount
        $table->string('status', 30)->default('active');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
