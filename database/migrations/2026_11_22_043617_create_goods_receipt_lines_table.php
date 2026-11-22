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
    Schema::create('goods_receipt_lines', function (Blueprint $table) {
        $table->id();
        $table->foreignId('goods_receipt_id')->constrained('goods_receipts')->onDelete('cascade');
        $table->foreignId('purchase_order_line_id')->constrained('purchase_order_lines')->onDelete('cascade');
        $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('restrict');
        $table->decimal('received_quantity', 12, 3);
        $table->decimal('unit_price', 14, 2);
        $table->decimal('line_total', 14, 2);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_lines');
    }
};
