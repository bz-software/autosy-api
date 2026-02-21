<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('id_subscription');

            $table->unsignedBigInteger('id_workshop');
            $table->unsignedBigInteger('id_subscription_plan');

            // ID da assinatura no Mercado Pago
            $table->string('mercado_pago_subscription_id')->nullable()->unique();

            // authorized | paused | cancelled | payment_failed
            $table->string('status')->default('pending');

            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('next_billing_at')->nullable();

            // importante para identificar via webhook
            $table->string('external_reference')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('id_workshop')
                ->references('id')
                ->on('workshops')
                ->onDelete('cascade');

            $table->foreign('id_subscription_plan')
                ->references('id_subscription_plan')
                ->on('subscription_plans')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};