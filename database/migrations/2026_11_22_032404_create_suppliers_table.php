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
    Schema::create('suppliers', function (Blueprint $table) {
        $table->id();
        $table->string('supplier_code', 50)->unique();
        $table->string('supplier_name', 150);
        $table->string('contact_person', 100)->nullable();
        $table->string('phone', 30)->nullable();
        $table->string('email', 100)->nullable();
        $table->text('address')->nullable();
        $table->string('payment_terms', 50)->nullable(); // Cash, Credit 30 days
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
        Schema::dropIfExists('suppliers');
    }
};
