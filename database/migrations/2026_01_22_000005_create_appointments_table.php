<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_workshop')
                ->constrained('workshops')
                ->cascadeOnDelete();

            $table->foreignId('id_customer')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('id_vehicle')
                ->constrained('vehicles')
                ->cascadeOnDelete();

            $table->string('license_plate');

            $table->string('status');
            $table->text('notes')->nullable();

            $table->date('appointment_date');

            $table->boolean('deleted')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
