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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->char('id', 36)->primary()->comment('ID');
            $table->char('product_id', 36)->comment('ProductID');
            $table->integer('quantity_change')->comment('quantity_change');
            $table->char('reason', 191)->comment('reason');
            $table->dateTime('transaction_date')->comment('transaction_date');
            $table->dateTimes();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
