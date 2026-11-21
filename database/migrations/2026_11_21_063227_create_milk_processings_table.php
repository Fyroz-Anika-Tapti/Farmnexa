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
    Schema::create('milk_processings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
        $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('set null');
        $table->date('processing_date');
        $table->string('product_name', 100);         // দই, ঘি, পনির, মিষ্টি
        $table->decimal('input_milk_liters', 10, 3); // কত লিটার দুধ ব্যবহার হলো
        $table->decimal('output_quantity', 10, 3);   // কত কেজি/লিটার উৎপাদন হলো
        $table->string('unit', 20)->default('kg');   // kg, liter, piece
        $table->decimal('processing_cost', 14, 2)->default(0); // অন্যান্য খরচ (গ্যাস, শ্রম)
        $table->decimal('selling_price_per_unit', 14, 2)->nullable();
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
        Schema::dropIfExists('milk_processings');
    }
};
