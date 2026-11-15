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
        
Schema::create('animals', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('farm_id');
    $table->unsignedBigInteger('section_id');
    $table->unsignedBigInteger('cost_centre_id')->nullable();

    $table->string('animal_code', 60)->unique();
    $table->string('animal_type', 50)->default('cattle');
    $table->string('breed', 100)->nullable();
    $table->string('sex', 20)->nullable();

    $table->date('date_of_birth')->nullable();
    $table->date('purchase_date')->nullable();
    $table->decimal('purchase_value', 14, 2)->nullable();

    $table->string('source_type', 30)->nullable();
    $table->string('status', 30)->default('active');

    $table->text('notes')->nullable();

    $table->timestamps();
    $table->softDeletes();

    // $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
    // $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
    // $table->foreign('cost_centre_id')->references('id')->on('cost_centres')->nullOnDelete();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
