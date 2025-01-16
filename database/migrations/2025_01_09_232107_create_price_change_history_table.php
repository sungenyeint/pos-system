<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('price_change_histories', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('product_id', 36);
            $table->integer('price_change');
            $table->char('status', 191);
            $table->dateTime('change_date');
            $table->dateTimes();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_change_histories');
    }
};
