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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('product_code', 50)->unique();
        $table->string('product_name', 150);
        $table->string('category', 50); // Milk, Egg, Meat, Processed, Other
        $table->string('unit', 20);     // liter, piece, kg
        $table->decimal('selling_price', 14, 2);
        $table->decimal('cost_price', 14, 2)->default(0);
        $table->integer('reorder_level')->default(0);
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
        Schema::dropIfExists('products');
    }
};
