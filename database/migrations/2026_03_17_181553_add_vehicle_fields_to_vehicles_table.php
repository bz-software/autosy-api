<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('brand', 100)->after('license_plate');

            $table->smallInteger('year')->nullable()->after('model');
            $table->string('engine', 50)->nullable()->after('year');
            $table->string('color', 50)->nullable()->after('engine');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'brand',
                'year',
                'engine',
                'color'
            ]);
        });
    }
};