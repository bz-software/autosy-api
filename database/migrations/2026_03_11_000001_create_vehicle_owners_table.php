<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_owners', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_vehicle');

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('id_customer');
            $table->index('id_vehicle');

            $table->foreign('id_customer')
                ->references('id')
                ->on('customers')
                ->cascadeOnDelete();

            $table->foreign('id_vehicle')
                ->references('id')
                ->on('vehicles')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_owners');
    }
};