<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('milk_productions', function (Blueprint $table) {
            $table->decimal('cost_per_liter', 10, 2)->default(50)->after('saleable_liters');
        });
    }

    public function down(): void
    {
        Schema::table('milk_productions', function (Blueprint $table) {
            $table->dropColumn('cost_per_liter');
        });
    }
};