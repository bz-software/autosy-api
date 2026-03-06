<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('id_workshop')
                ->constrained('workshops')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('type');

            $table->unsignedTinyInteger('category');

            $table->unsignedTinyInteger('source_type')->nullable();

            $table->foreignId('id_appointment')
                ->nullable()
                ->constrained('appointments')
                ->nullOnDelete();

            $table->unsignedBigInteger('id_inventory_movement')->nullable();

            $table->decimal('amount', 15, 2);

            $table->unsignedTinyInteger('payment_method');

            $table->date('transaction_date');

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->softDeletes();

            $table->index('id_workshop');
            $table->index('transaction_date');
            $table->index('type');
            $table->index('category');
            $table->index('payment_method');
            $table->index('id_inventory_movement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
