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
        Schema::create('purchases', function (Blueprint $table) {
            $table->char('id', 36)->primary()->comment('ID');
            $table->char('product_id', 36)->comment('ProductID');
            $table->integer('quantity')->default(1)->comment('quantity');
            $table->decimal('total_cost', 10, 2)->comment('total_cost');
            $table->dateTime('purchase_date')->comment('purchase_date');
            $table->dateTimes();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
