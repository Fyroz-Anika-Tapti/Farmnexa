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
        Schema::create('farms', function (Blueprint $table) {
                $table->id();
                $table->string('farm_name', 180);
                $table->string('farm_code', 50)->unique();
                $table->string('location', 255)->nullable();
                $table->string('phone', 30)->nullable();
                $table->char('currency_code', 3)->default('BDT');
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
        Schema::dropIfExists('farms');
    }
};
