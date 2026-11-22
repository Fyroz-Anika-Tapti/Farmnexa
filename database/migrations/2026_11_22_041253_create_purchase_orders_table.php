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
    Schema::create('purchase_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
        $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
        $table->string('po_number', 50)->unique();
        $table->date('po_date');
        $table->date('expected_delivery_date')->nullable();
        $table->decimal('subtotal', 14, 2)->default(0);
        $table->decimal('tax_amount', 14, 2)->default(0);
        $table->decimal('discount_amount', 14, 2)->default(0);
        $table->decimal('total_amount', 14, 2)->default(0);
        $table->string('status', 30)->default('pending');
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
