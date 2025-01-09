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
        Schema::create('products', function (Blueprint $table) {
            $table->char('id', 36)->primary()->comment('ID');
            $table->char('category_id', 36)->nullable()->comment('CategoryID');
            $table->string('name', 191)->comment('name');
            $table->decimal('unit_cost', 10, 2)->comment('unit_cost');
            $table->decimal('unit_price', 10, 2)->comment('unit_price');
            $table->integer('stock_quantity')->default(1)->comment('stock_quantity');
            $table->dateTimes();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
