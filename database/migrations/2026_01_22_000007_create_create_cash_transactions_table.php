<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {

            // 🔑 ID
            $table->id();

            // 🏢 Empresa (Obrigatório)
            $table->foreignId('id_workshop')
                ->constrained('workshops')
                ->cascadeOnDelete();

            // 🔄 Tipo (income / expense)
            $table->unsignedTinyInteger('type');

            // 🗂 Categoria
            $table->unsignedTinyInteger('category');

            // 🔗 Origem (nullable)
            $table->unsignedTinyInteger('source_type')->nullable();

            // 🔗 Agendamento (nullable)
            $table->foreignId('id_appointment')
                ->nullable()
                ->constrained('appointments')
                ->nullOnDelete();

            // 🔗 Movimentação de estoque (externa)
            $table->unsignedBigInteger('id_inventory_movement')->nullable();

            // 💵 Valor (sempre positivo)
            $table->decimal('amount', 15, 2);

            // 💳 Forma de pagamento
            $table->unsignedTinyInteger('payment_method');

            // 📅 Data real da movimentação
            $table->date('transaction_date');

            // 📝 Observações
            $table->text('notes')->nullable();

            // 👤 Usuário que criou
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            // ⏱ Datas padrão
            $table->timestamps();

            // 🗑 Soft Delete
            $table->softDeletes();


            /*
             |--------------------------------------------------------------------------
             | INDEXES (Performance)
             |--------------------------------------------------------------------------
             */

            $table->index('id_workshop');
            $table->index('transaction_date');
            $table->index('type');
            $table->index('category');
            $table->index('payment_method');
            $table->index('id_inventory_movement');


            /*
             |--------------------------------------------------------------------------
             | CONSTRAINT para garantir valor positivo
             |--------------------------------------------------------------------------
             */

            $table->check('amount > 0');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
