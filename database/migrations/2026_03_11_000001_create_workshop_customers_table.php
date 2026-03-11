<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_customers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_workshop');
            $table->unsignedBigInteger('id_customer');

            $table->timestamps();

            $table->index('id_workshop');
            $table->index('id_customer');

            $table->unique(
                ['id_workshop', 'id_customer'],
                'unique_workshop_customer'
            );

            $table->foreign('id_workshop')
                ->references('id')
                ->on('workshops')
                ->cascadeOnDelete();

            $table->foreign('id_customer')
                ->references('id')
                ->on('customers')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_customers');
    }
};