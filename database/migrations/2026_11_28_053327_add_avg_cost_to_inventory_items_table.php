<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->decimal('average_cost', 14, 4)->default(0)->after('current_stock');
            $table->decimal('stock_value', 14, 2)->default(0)->after('average_cost');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn(['average_cost', 'stock_value']);
        });
    }
};