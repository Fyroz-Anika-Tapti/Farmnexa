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
        Schema::create('animal_health_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animals')->onDelete('cascade');
            $table->string('event_type', 50); // Vaccination, Treatment, Check-up, Illness
            $table->date('event_date');
            $table->string('diagnosis', 255)->nullable();
            $table->text('treatment_description')->nullable();
            $table->decimal('medicine_cost', 14, 2)->default(0);
            $table->string('veterinarian_name', 150)->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_health_events');
    }
};
