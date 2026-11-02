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
        Schema::create('populations', function (Blueprint $table) {
        $table->id();                       // file has no id, so auto-increment
        $table->unsignedBigInteger('townid');
        $table->integer('ryear');
        $table->integer('women')->nullable();
        $table->integer('total')->nullable();

        $table->foreign('townid')->references('id')->on('towns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('populations');
    }
};
