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
        Schema::create('animal_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animals')->onDelete('cascade');
            $table->string('customer_name', 150);
            $table->string('customer_phone', 30)->nullable();
            $table->date('sale_date');
            $table->decimal('carrying_value', 14, 2)->default(0);
            $table->decimal('selling_price', 14, 2);
            $table->decimal('proceeds_received', 14, 2)->default(0);
            $table->string('status', 30)->default('completed');
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
        Schema::dropIfExists('animal_sales');
    }
};
