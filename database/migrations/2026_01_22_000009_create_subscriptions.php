<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_subscription_plan');

            // ID da assinatura no Stripe
            $table->string('id_stripe_subscription', 150)->unique();

            // pending | authorized | paused | cancelled | payment_failed
            $table->string('status')->default('pending');

            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();

            $table->boolean('cancel_at_period_end')->default(false);

            $table->timestamps();

            // Foreign keys
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('id_subscription_plan')
                ->references('id')
                ->on('subscription_plans')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};