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
        Schema::create('milk_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('animal_id')->nullable()->constrained('animals')->onDelete('set null'); // Individual animal (optional)
            $table->foreignId('production_group_id')->nullable(); // We'll add the FK later if needed
            $table->date('production_date');
            $table->enum('shift', ['morning', 'evening']); // সকাল বা বিকাল
            $table->decimal('produced_liters', 10, 3);
            $table->decimal('rejected_liters', 10, 3)->default(0);
            $table->decimal('saleable_liters', 10, 3); // Auto-calculated
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
        Schema::dropIfExists('milk_productions');
    }
};
