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
    Schema::create('sales_invoice_lines', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sales_invoice_id')->constrained('sales_invoices')->onDelete('cascade');
        $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
        $table->decimal('quantity', 12, 3);
        $table->decimal('unit_price', 14, 2);
        $table->decimal('line_total', 14, 2);
        $table->decimal('cost_price', 14, 2)->default(0);
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoice_lines');
    }
};
