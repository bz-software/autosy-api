<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign('vehicles_id_customer_foreign');
            $table->dropUnique('vehicles_id_customer_license_plate_unique');
            $table->dropColumn('id_customer');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer');

            $table->foreign('id_customer')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->unique(['id_customer', 'license_plate']);
        });
    }
};
