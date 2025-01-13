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
        Schema::create('import_details', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('import_id', 36);
            $table->unsignedMediumInteger('line_number');
            $table->boolean('result')->default(0);
            $table->text('messages')->nullable();
            $table->dateTimes();

            $table->foreign('import_id')->references('id')->on('imports')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_details');
    }
};
