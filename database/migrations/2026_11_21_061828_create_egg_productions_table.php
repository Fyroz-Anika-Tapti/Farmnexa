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
    Schema::create('egg_productions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
        $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
        $table->foreignId('batch_id')->nullable()->constrained('poultry_batches')->onDelete('set null');
        $table->date('production_date');
        $table->enum('shift', ['morning', 'afternoon', 'evening']); // ডিম সাধারণত ৩ শিফটে সংগ্রহ হয়
        $table->integer('produced_eggs');
        $table->integer('rejected_eggs')->default(0);
        $table->integer('saleable_eggs'); // Auto-calculated
        $table->decimal('total_weight_kg', 8, 2)->nullable();
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
        Schema::dropIfExists('egg_productions');
    }
};
