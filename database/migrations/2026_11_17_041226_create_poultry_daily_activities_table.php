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
        Schema::create('poultry_daily_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('poultry_batches')->onDelete('cascade');
            $table->date('activity_date');
            $table->integer('mortality')->default(0);
            $table->integer('culling')->default(0);
            $table->integer('transfer_in')->default(0);
            $table->integer('transfer_out')->default(0);
            $table->integer('closing_birds')->default(0); 
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
        Schema::dropIfExists('poultry_daily_activities');
    }
};
