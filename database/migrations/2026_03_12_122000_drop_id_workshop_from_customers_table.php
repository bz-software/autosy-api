<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_id_workshop_foreign');
            $table->dropColumn('id_workshop');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('id_workshop');

            $table->foreign('id_workshop')
                ->references('id')
                ->on('workshops')
                ->onDelete('cascade');
        });
    }
};