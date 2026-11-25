<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animal_sales', function (Blueprint $table) {
            // carrying_value আগে থেকেই আছে
            $table->decimal('profit_percentage', 8, 2)->nullable()->after('carrying_value');
            $table->decimal('profit_amount', 14, 2)->nullable()->after('selling_price');
        });
    }

    public function down(): void
    {
        Schema::table('animal_sales', function (Blueprint $table) {
            $table->dropColumn(['profit_percentage', 'profit_amount']);
        });
    }
};