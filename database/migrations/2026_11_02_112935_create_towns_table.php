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
        Schema::create('towns', function (Blueprint $table) {
        $table->unsignedBigInteger('id')->primary();
        $table->string('tname', 100);
        $table->unsignedBigInteger('countyid');
        $table->boolean('countyseat')->default(false);
        $table->boolean('countylevel')->default(false);

        $table->foreign('countyid')->references('id')->on('counties');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('towns');
    }
};
