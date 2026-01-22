<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_customer')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->string('license_plate');
            $table->string('model');

            $table->timestamps();

            $table->unique(['customer_id', 'license_plate']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
