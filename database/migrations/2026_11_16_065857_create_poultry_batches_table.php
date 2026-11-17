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
        Schema::create('poultry_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained('farms')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('cost_centre_id')->nullable()->constrained('cost_centres')->onDelete('set null');
            $table->string('batch_code', 60)->unique();
            $table->string('bird_type', 60); // Broiler, Layer, Sonali
            $table->string('breed', 100)->nullable();
            $table->integer('opening_birds');
            $table->date('placement_date');
            $table->string('source_supplier', 150)->nullable();
            $table->string('status', 30)->default('active');
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
        Schema::dropIfExists('poultry_batches');
    }
};
