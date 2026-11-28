<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('milk_processings', function (Blueprint $table) {
            $table->decimal('milk_cost', 14, 2)->default(0)->after('processing_cost');
        });
    }

    public function down(): void
    {
        Schema::table('milk_processings', function (Blueprint $table) {
            $table->dropColumn('milk_cost');
        });
    }
};