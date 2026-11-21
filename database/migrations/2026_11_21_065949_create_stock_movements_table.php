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
    Schema::create('stock_movements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
        $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
        $table->date('movement_date');
        $table->enum('movement_type', ['in', 'out', 'adjustment']);
        $table->decimal('quantity', 12, 3);
        $table->decimal('unit_price', 14, 2)->default(0);
        $table->decimal('total_value', 14, 2)->default(0);
        $table->decimal('stock_before', 12, 3)->default(0);
        $table->decimal('stock_after', 12, 3)->default(0);
        $table->string('reference_type', 30)->nullable(); // purchase, issue, adjustment
        $table->string('reference_no', 100)->nullable();
        $table->text('notes')->nullable();
        $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
