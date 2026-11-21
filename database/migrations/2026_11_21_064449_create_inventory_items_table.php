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
    Schema::create('inventory_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
        $table->string('item_code', 50)->unique();
        $table->string('item_name', 150);
        $table->string('category', 50); // Feed, Medicine, Equipment, Other
        $table->string('unit', 20);     // kg, liter, piece, bag
        $table->decimal('opening_stock', 12, 3)->default(0);
        $table->decimal('current_stock', 12, 3)->default(0);
        $table->decimal('reorder_level', 12, 3)->default(0);
        $table->decimal('purchase_price', 14, 2)->default(0);
        $table->text('description')->nullable();
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
        Schema::dropIfExists('inventory_items');
    }
};
